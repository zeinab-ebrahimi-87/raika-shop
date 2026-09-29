<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'images.*' => 'nullable|image|max:2048',
            'variants.*.label' => 'nullable|string|max:100',
            'variants.*.price' => 'nullable|integer|min:0',
            'variants.*.stock' => 'nullable|integer|min:0',
        ]);

        $data = $request->except(['image', 'images', 'variants']);
        $data['slug'] = Str::slug($request->name) . '-' . time();
        $data['is_active'] = $request->has('is_active');
        $data['is_featured'] = $request->has('is_featured');

        // عکس اصلی
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/products'), $filename);
            $data['image'] = $filename;
        }

        $product = Product::create($data);

        // عکس‌های گالری
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $filename = time() . '_' . $index . '_' . $file->getClientOriginalName();
                $file->move(public_path('images/products'), $filename);
                
                $product->images()->create([
                    'image' => $filename,
                    'sort_order' => $index,
                ]);
            }
        }

        // ⚠️ Variant ها
        if ($request->has('variants')) {
            foreach ($request->variants as $index => $variantData) {
                if (!empty($variantData['label']) && isset($variantData['price'])) {
                    $product->variants()->create([
                        'label' => $variantData['label'],
                        'price' => $variantData['price'],
                        'stock' => $variantData['stock'] ?? 0,
                        'sort_order' => $index,
                        'is_default' => $index === 0, // اولین = پیش‌فرض
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'محصول با موفقیت اضافه شد.');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'images.*' => 'nullable|image|max:2048',
            'variants.*.label' => 'nullable|string|max:100',
            'variants.*.price' => 'nullable|integer|min:0',
            'variants.*.stock' => 'nullable|integer|min:0',
        ]);

        $data = $request->except(['image', 'images', 'variants']);
        $data['is_active'] = $request->has('is_active');
        $data['is_featured'] = $request->has('is_featured');

        // عکس اصلی جدید
        if ($request->hasFile('image')) {
            if ($product->image) {
                $oldPath = public_path('images/products/' . $product->image);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/products'), $filename);
            $data['image'] = $filename;
        }

        $product->update($data);

        // عکس‌های گالری جدید
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $filename = time() . '_' . $index . '_' . $file->getClientOriginalName();
                $file->move(public_path('images/products'), $filename);
                
                $product->images()->create([
                    'image' => $filename,
                    'sort_order' => $product->images()->count() + $index,
                ]);
            }
        }

        // ⚠️ Variant ها
        if ($request->has('variants')) {
            // پاک کردن قبلی‌ها
            $product->variants()->delete();
            
            // اضافه کردن جدیدها
            $index = 0;
            foreach ($request->variants as $variantData) {
                if (!empty($variantData['label']) && isset($variantData['price'])) {
                    $product->variants()->create([
                        'label' => $variantData['label'],
                        'price' => $variantData['price'],
                        'stock' => $variantData['stock'] ?? 0,
                        'sort_order' => $index,
                        'is_default' => $index === 0,
                    ]);
                    $index++;
                }
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'محصول ویرایش شد.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // پاک کردن عکس اصلی
        if ($product->image) {
            $mainImagePath = public_path('images/products/' . $product->image);
            if (file_exists($mainImagePath)) {
                unlink($mainImagePath);
            }
        }
        
        // پاک کردن عکس‌های گالری
        foreach ($product->images as $galleryImage) {
            $galleryPath = public_path('images/products/' . $galleryImage->image);
            if (file_exists($galleryPath)) {
                unlink($galleryPath);
            }
        }

        // پاک کردن variant ها (با cascade خودکار)
        $product->delete();

        return back()->with('success', 'محصول و همه عکس‌هاش حذف شدن.');
    }

    public function destroyImage($id)
    {
        $image = ProductImage::findOrFail($id);
        
        // حذف فایل فیزیکی از public
        $path = public_path('images/products/' . $image->image);
        if (file_exists($path)) {
            unlink($path);
        }
        
        // حذف رکورد از دیتابیس
        $image->delete();
        
        return back()->with('success', 'عکس با موفقیت حذف شد');
    }

    public function search(Request $request)
    {
        $q = $request->get('q');
        
        if (empty($q)) {
            return response()->json([]);
        }
        
        $products = Product::where('name', 'like', "%{$q}%")
            ->take(10)
            ->get();
        
        return response()->json($products->map(function($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => number_format($product->price) . ' ت',
                'image' => $product->image && file_exists(public_path('images/products/' . $product->image))
                    ? asset('images/products/' . $product->image)
                    : asset('images/placeholder.jpg'),
                'edit_url' => route('admin.products.edit', $product->id),
            ];
        }));
    }
}