<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // فرم Checkout
    public function checkout()
    {
        $cart = session()->get('cart', []);
        
        if (count($cart) === 0) {
            return redirect()->route('cart.index')->with('error', 'سبد خریدت خالیه.');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['qty'];
        }

        return view('checkout', compact('cart', 'total'));
    }

    // ثبت سفارش
    public function store(Request $request)
    {
        $cart = session()->get('cart', []);
        
        if (count($cart) === 0) {
            return redirect()->route('cart.index')->with('error', 'سبد خریدت خالیه.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'city' => 'required|string|max:100',
            'address' => 'required|string',
            'postal_code' => 'required|string|max:10',
            'notes' => 'nullable|string',
        ], [
            'name.required' => 'نام الزامی است.',
            'phone.required' => 'شماره تماس الزامی است.',
            'city.required' => 'شهر الزامی است.',
            'address.required' => 'آدرس الزامی است.',
            'postal_code.required' => 'کد پستی الزامی است.',
        ]);

        try {
            DB::beginTransaction();

            // جمع کل
            $total = 0;
            foreach ($cart as $item) {
                $total += $item['price'] * $item['qty'];
            }

            // ساخت سفارش
            $order = Order::create([
                'user_id' => Auth::id(),
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'city' => $request->city,
                'address' => $request->address,
                'postal_code' => $request->postal_code,
                'total' => $total,
                'status' => 'pending',
                'notes' => $request->notes,
            ]);

            // ساخت آیتم‌ها
            foreach ($cart as $item) {
                $order->items()->create([
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'price' => $item['price'],
                    'qty' => $item['qty'],
                ]);

                // کم کردن موجودی
                $product = Product::find($item['id']);
                if ($product) {
                    $product->decrement('stock', $item['qty']);
                }
            }

            DB::commit();

            // خالی کردن سبد
            session()->forget('cart');

            return redirect()->route('order.success', $order->id)
                ->with('success', 'سفارش شما با موفقیت ثبت شد!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'خطا در ثبت سفارش. دوباره تلاش کن.');
        }
    }

    // صفحه موفقیت
    public function success($id)
    {
        $order = Order::with('items')->findOrFail($id);
        return view('order-success', compact('order'));
    }
}