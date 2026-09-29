<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // نمایش سبد خرید
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['qty'];
        }

        return view('cart.index', compact('cart', 'total'));
    }

    // افزودن به سبد
    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        // ⚠️ چک کن variant انتخاب شده
        $variantId = $request->input('variant_id');
        $variant = null;
        
        if ($product->variants->count() > 0) {
            if (!$variantId) {
                return back()->with('error', 'لطفاً یه وزن/تعداد انتخاب کن.');
            }
            
            $variant = $product->variants()->find($variantId);
            
            if (!$variant) {
                return back()->with('error', 'وزن/تعداد انتخاب شده معتبر نیست.');
            }
            
            if ($variant->stock <= 0) {
                return back()->with('error', 'این وزن/تعداد ناموجوده.');
            }
            
            $price = $variant->price;
            $availableStock = $variant->stock;
            $variantLabel = $variant->label;
        } else {
            $price = $product->price;
            $availableStock = $product->stock;
            $variantLabel = null;
        }
        
        if ($availableStock <= 0) {
            return back()->with('error', 'این محصول موجود نیست.');
        }
        
        $cart = session()->get('cart', []);
        $key = $variantId ? 'product-' . $product->id . '-variant-' . $variantId : 'product-' . $product->id;
        
        if (isset($cart[$key])) {
            if ($cart[$key]['qty'] < $availableStock) {
                $cart[$key]['qty']++;
            } else {
                return back()->with('error', 'بیشتر از موجودی نمیشه اضافه کرد.');
            }
        } else {
            $cart[$key] = [
                'id' => $product->id,
                'name' => $product->name . ($variantLabel ? ' - ' . $variantLabel : ''),
                'price' => $price,
                'qty' => 1,
                'slug' => $product->slug,
                'variant_id' => $variantId,
                'variant_label' => $variantLabel,
            ];
        }
        
        session()->put('cart', $cart);
        
        return back()->with('success', 'محصول به سبد خرید اضافه شد.');
    }
    // تغییر تعداد
    public function update(Request $request, $key)
    {
        $request->validate([
            'qty' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            $product = Product::find($cart[$key]['id']);
            
            if ($request->qty > $product->stock) {
                return back()->with('error', 'بیشتر از موجودی نمیشه.');
            }

            $cart[$key]['qty'] = $request->qty;
            session()->put('cart', $cart);
        }

        return back()->with('success', 'سبد خرید آپدیت شد.');
    }

    // حذف از سبد
    public function remove($key)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'محصول از سبد حذف شد.');
    }

    // خالی کردن سبد
    public function clear()
    {
        session()->forget('cart');
        return back()->with('success', 'سبد خرید خالی شد.');
    }
}
