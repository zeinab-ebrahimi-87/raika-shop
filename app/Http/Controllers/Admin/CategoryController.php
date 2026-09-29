<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // ============================================
    // لیست دسته‌بندی‌ها
    // ============================================
    public function index()
    {
        $categories = Category::withCount('products')->latest()->get();
        $trashedCategories = Category::onlyTrashed()->withCount('products')->latest()->get();
        
        return view('admin.categories.index', compact('categories', 'trashedCategories'));
    }

    // ============================================
    // افزودن
    // ============================================
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ], [
            'name.required' => 'نام دسته‌بندی الزامی است.',
            'name.unique' => 'این اسم قبلاً استفاده شده.',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . time(),
            'is_active' => true,
        ]);

        return back()->with('success', 'دسته‌بندی اضافه شد.');
    }

    // ============================================
    // ویرایش
    // ============================================
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
        ], [
            'name.required' => 'نام دسته‌بندی الزامی است.',
            'name.unique' => 'این اسم قبلاً استفاده شده.',
        ]);

        $category->update([
            'name' => $request->name,
        ]);

        return back()->with('success', 'دسته‌بندی ویرایش شد.');
    }

    // ============================================
    // حذف (با Reassign)
    // ============================================
    public function destroy(Request $request, $id)
    {
        $category = Category::withCount('products')->findOrFail($id);

        // چک: دسته "بدون دسته" حذف نشه
        if ($category->slug === 'uncategorized') {
            return back()->with('error', 'دسته‌ی "بدون دسته" رو نمیشه حذف کرد.');
        }

        // اگه محصول نداره → مستقیم حذف
        if ($category->products_count === 0) {
            $category->delete();
            return back()->with('success', 'دسته‌بندی حذف شد.');
        }

        // اگه محصول داره → منتقل کن
        $targetCategoryId = $request->input('target_category_id');

        if (!$targetCategoryId) {
            return back()->with('error', 'لطفاً دسته‌ی مقصد رو انتخاب کن.');
        }

        $targetCategory = Category::findOrFail($targetCategoryId);

        if ($targetCategory->id === $category->id) {
            return back()->with('error', 'نمی‌تونی به خودش منتقل کنی.');
        }

        // انتقال همه محصولات
        Product::where('category_id', $category->id)
            ->update(['category_id' => $targetCategory->id]);

        // بعد حذف
        $category->delete();

        return back()->with('success', "دسته حذف شد و محصولاتش به «{$targetCategory->name}» منتقل شدن.");
    }

    // ============================================
    // بازگردانی (Restore)
    // ============================================
    public function restore($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();

        return back()->with('success', 'دسته‌بندی بازگردانی شد.');
    }

    // ============================================
    // حذف کامل (Force Delete)
    // ============================================
    public function forceDelete($id)
    {
        $category = Category::onlyTrashed()->withCount('products')->findOrFail($id);

        if ($category->products_count > 0) {
            return back()->with('error', 'این دسته محصول داره. اول محصولاتش رو جابه‌جا کن.');
        }

        $category->forceDelete();

        return back()->with('success', 'دسته‌بندی کامل حذف شد.');
    }

    // ============================================
    // فعال/غیرفعال
    // ============================================
    public function toggleActive($id)
    {
        $category = Category::findOrFail($id);
        $category->update(['is_active' => !$category->is_active]);

        $status = $category->is_active ? 'فعال' : 'غیرفعال';
        return back()->with('success', "دسته‌بندی {$status} شد.");
    }
}