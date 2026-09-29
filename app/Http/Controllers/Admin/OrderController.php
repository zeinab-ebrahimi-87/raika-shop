<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items');
        
        // فیلتر وضعیت
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // مرتب‌سازی
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'expensive':
                $query->orderBy('total', 'desc');
                break;
            case 'cheap':
                $query->orderBy('total', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }
        
        $orders = $query->paginate(15);
        
        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with('items')->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        return back()->with('success', 'وضعیت سفارش آپدیت شد.');
    }

    public function search(Request $request)
    {
        $q = $request->get('q');
        
        if (empty($q)) {
            return response()->json([]);
        }
        
        $orders = Order::where('name', 'like', "%{$q}%")
            ->orWhere('phone', 'like', "%{$q}%")
            ->orWhere('id', $q)
            ->take(10)
            ->get();
        
        return response()->json($orders->map(function($order) {
            return [
                'id' => $order->id,
                'name' => $order->name,
                'phone' => $order->phone,
                'total' => number_format($order->total) . ' ت',
                'url' => route('admin.orders.show', $order->id),
            ];
        }));
    }
    
    
}