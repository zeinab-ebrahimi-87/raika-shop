<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'products' => Product::count(),
            'orders' => Order::count(),
            'users' => User::count(),
            'categories' => Category::count(),
            'revenue' => Order::where('status', '!=', 'cancelled')->sum('total'),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'pending_reviews' => \App\Models\Review::where('is_approved', false)->count(),
        ];

        $recentOrders = Order::latest()->take(5)->get();

        // ============================================
        // آمار ۷ روز اخیر
        // ============================================
        $chartData = [];
        $maxSales = 0;
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dayName = $this->getPersianDayName($date->dayOfWeek);
            
            $sales = Order::whereDate('created_at', $date->toDateString())
                ->where('status', '!=', 'cancelled')
                ->sum('total');
            
            $count = Order::whereDate('created_at', $date->toDateString())
                ->where('status', '!=', 'cancelled')
                ->count();
            
            if ($sales > $maxSales) {
                $maxSales = $sales;
            }
            
            $chartData[] = [
                'date' => $date->format('m/d'),
                'day_name' => $dayName,
                'sales' => $sales,
                'count' => $count,
            ];
        }
        
        // محاسبه درصد برای نمودار
        foreach ($chartData as &$item) {
            $item['percent'] = $maxSales > 0 ? ($item['sales'] / $maxSales) * 100 : 0;
        }
        unset($item);

        return view('admin.dashboard', compact('stats', 'recentOrders', 'chartData', 'maxSales'));
    }

    private function getPersianDayName($dayOfWeek)
    {
        $days = [
            0 => 'یکشنبه',
            1 => 'دوشنبه',
            2 => 'سه‌شنبه',
            3 => 'چهارشنبه',
            4 => 'پنجشنبه',
            5 => 'جمعه',
            6 => 'شنبه',
        ];
        
        return $days[$dayOfWeek] ?? '';
    }
}