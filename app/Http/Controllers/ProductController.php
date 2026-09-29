<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // نمایش همه محصولات
   public function index(Request $request)
    {
        $query = Product::where('is_active', true);
        
        // فیلتر دسته‌بندی
        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }
        
        // فیلتر موجودی
        if ($request->filled('in_stock') && $request->in_stock == '1') {
            $query->where('stock', '>', 0);
        }
        
        // فیلتر ویژه
        if ($request->filled('featured') && $request->featured == '1') {
            $query->where('is_featured', true);
        }
        
        // مرتب‌سازی
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'cheap':
                $query->orderBy('price', 'asc');
                break;
            case 'expensive':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'popular':
                $query->orderBy('created_at', 'desc');  // فعلاً همین
                break;
            case 'discount':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }
        
        $products = $query->get();
        $categories = Category::withCount('products')->get();
        
        return view('products.index', compact('products', 'categories', 'sort'));
    }

    // نمایش یه محصول خاص
    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        return view('products.show', compact('product'));
    }

    // نمایش محصولات یه دسته
    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $products = $category->products()
            ->where('is_active', true)
            ->withCount('reviews')
            ->get();

        return view('products.category', compact('products', 'category'));
    }


    // ======== جستجوی زنده ========
    public function search(Request $request)
    {
        $q = $request->get('q');
        
        if (empty($q) || strlen($q) < 2) {
            return response()->json([]);
        }
        
        $products = Product::where('is_active', true)
            ->where(function($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            })
            ->take(8)
            ->get();
        
        return response()->json($products->map(function($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => number_format($product->price) . ' تومان',
                'image' => $product->image && file_exists(public_path('images/products/' . $product->image))
                    ? asset('images/products/' . $product->image)
                    : asset('images/placeholder.jpg'),
                'url' => route('products.show', $product->slug),
            ];
        }));
    }
    
}