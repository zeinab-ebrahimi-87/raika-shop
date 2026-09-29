@extends('layouts.app')

@section('title', 'داشبورد ادمین | رایکا')

@section('content')

<div class="admin-dashboard">
    
    <!-- هدر داشبورد -->
    <div class="admin-header">
        <div>
            <h1 class="admin-title">🎛 داشبورد مدیریت</h1>
            <p class="admin-subtitle">خوش آمدی، {{ Auth::user()->name }} 👋</p>
        </div>
        <div class="admin-date">
            {{ now()->format('Y/m/d') }}
        </div>
    </div>
    
    <!-- کارت‌های آماری -->
    <div class="stats-grid">
        <div class="stat-card stat-products">
            <div class="stat-icon">📦</div>
            <div class="stat-info">
                <h3>{{ $stats['products'] }}</h3>
                <p>محصول</p>
            </div>
            <div class="stat-bg"></div>
        </div>
        
        <div class="stat-card stat-orders">
            <div class="stat-icon">🛒</div>
            <div class="stat-info">
                <h3>{{ $stats['orders'] }}</h3>
                <p>سفارش</p>
            </div>
            <div class="stat-bg"></div>
        </div>
        
        <div class="stat-card stat-users">
            <div class="stat-icon">👥</div>
            <div class="stat-info">
                <h3>{{ $stats['users'] }}</h3>
                <p>کاربر</p>
            </div>
            <div class="stat-bg"></div>
        </div>
        
        <div class="stat-card stat-categories">
            <div class="stat-icon">📂</div>
            <div class="stat-info">
                <h3>{{ $stats['categories'] }}</h3>
                <p>دسته‌بندی</p>
            </div>
            <div class="stat-bg"></div>
        </div>
        
        <div class="stat-card stat-revenue">
            <div class="stat-icon">💰</div>
            <div class="stat-info">
                <h3>{{ number_format($stats['revenue']) }}</h3>
                <p>تومان درآمد</p>
            </div>
            <div class="stat-bg"></div>
        </div>
        
        <div class="stat-card stat-pending">
            <div class="stat-icon">⏳</div>
            <div class="stat-info">
                <h3>{{ $stats['pending_orders'] }}</h3>
                <p>در انتظار</p>
            </div>
            <div class="stat-bg"></div>
        </div>

        <div class="stat-card stat-pending">
            <div class="stat-icon">💬</div>
            <div class="stat-info">
                <h3>{{ $stats['pending_reviews'] }}</h3>
                <p>نظر در انتظار</p>
            </div>
            <div class="stat-bg"></div>
        </div>
    </div>


    <!-- ============================================
        نمودار فروش ۷ روز اخیر
        ============================================ -->
    <div class="sales-chart-section">
        <div class="chart-header">
            <h2 class="section-heading">📊 فروش ۷ روز اخیر</h2>
            <div class="chart-total">
                <span class="chart-total-label">جمع:</span>
                <span class="chart-total-value">{{ number_format($maxSales > 0 ? collect($chartData)->sum('sales') : 0) }} تومان</span>
            </div>
        </div>
        
        <div class="chart-container">
            <div class="chart-bars">
                @foreach($chartData as $index => $day)
                    <div class="chart-bar-wrapper" style="--delay: {{ $index * 0.1 }}s;">
                        <div class="chart-bar-tooltip">
                            <div class="tooltip-date">{{ $day['day_name'] }} {{ $day['date'] }}</div>
                            <div class="tooltip-sales">{{ number_format($day['sales']) }} تومان</div>
                            <div class="tooltip-count">{{ $day['count'] }} سفارش</div>
                        </div>
                        <div class="chart-bar-value">{{ number_format($day['sales']) }}</div>
                        <div class="chart-bar">
                            <div class="chart-bar-fill" style="height: {{ $day['percent'] }}%;"></div>
                        </div>
                        <div class="chart-bar-label">
                            <span class="day-name">{{ $day['day_name'] }}</span>
                            <span class="day-date">{{ $day['date'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>


    
    <!-- دکمه‌های سریع -->
    <div class="quick-actions">
        <h2 class="section-heading">⚡ دسترسی سریع</h2>
        <div class="actions-grid">
            <a href="{{ route('admin.products.index') }}" class="action-card">
                <div class="action-icon">📦</div>
                <div class="action-text">مدیریت محصولات</div>
            </a>
            <a href="{{ route('admin.products.create') }}" class="action-card action-primary">
                <div class="action-icon">➕</div>
                <div class="action-text">افزودن محصول</div>
            </a>

            <a href="{{ route('admin.categories.index') }}" class="action-card">
                <div class="action-icon">📂</div>
                <div class="action-text">دسته‌بندی‌ها</div>
            </a>

            <a href="{{ route('admin.orders.index') }}" class="action-card">
                <div class="action-icon">🛒</div>
                <div class="action-text">مدیریت سفارشات</div>
            </a>

            <a href="{{ route('admin.reviews.index') }}" class="action-card">
                <div class="action-icon">💬</div>
                <div class="action-text">مدیریت نظرات</div>
            </a>

            <a href="{{ route('home') }}" class="action-card">
                <div class="action-icon">🏠</div>
                <div class="action-text">مشاهده سایت</div>
            </a>
        </div>
   
    </div>
    
    <!-- آخرین سفارشات -->
    <div class="recent-orders">
        <div class="orders-header">
            <h2 class="section-heading">📋 آخرین سفارشات</h2>
            <a href="{{ route('admin.orders.index') }}" class="view-all">مشاهده همه ←</a>
        </div>
        
        @if($recentOrders->count() > 0)
        <div class="orders-table-wrapper">
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>مشتری</th>
                        <th>مبلغ</th>
                        <th>وضعیت</th>
                        <th>تاریخ</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                    <tr>
                        <td><strong>#{{ $order->id }}</strong></td>
                        <td>{{ $order->name }}</td>
                        <td>{{ number_format($order->total) }} ت</td>
                        <td>
                            @php
                                $statusMap = [
                                    'pending' => ['label' => 'در انتظار', 'class' => 'status-pending'],
                                    'processing' => ['label' => 'در حال پردازش', 'class' => 'status-processing'],
                                    'shipped' => ['label' => 'ارسال شده', 'class' => 'status-shipped'],
                                    'delivered' => ['label' => 'تحویل شده', 'class' => 'status-delivered'],
                                    'cancelled' => ['label' => 'لغو شده', 'class' => 'status-cancelled'],
                                ];
                                $status = $statusMap[$order->status] ?? ['label' => $order->status, 'class' => ''];
                            @endphp
                            <span class="status-badge {{ $status['class'] }}">{{ $status['label'] }}</span>
                        </td>
                        <td>{{ $order->created_at->format('Y/m/d') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="table-btn">
                                👁
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-orders">
            <div class="empty-icon">📭</div>
            <p>هنوز سفارشی ثبت نشده</p>
        </div>
        @endif
    </div>
    
</div>

<style>
/* ============ داشبورد ============ */
.admin-dashboard {
    max-width: 1300px;
    margin: 0 auto;
    animation: fadeInUp 0.6s ease-out;
}

/* هدر */
.admin-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 40px;
    flex-wrap: wrap;
    gap: 20px;
}

.admin-title {
    font-size: 2.5em;
    color: #603F26;
    margin-bottom: 8px;
}

.admin-subtitle {
    color: #825B32;
    font-size: 1.05em;
}

.admin-date {
    background: linear-gradient(135deg, #603F26, #825B32);
    color: #FFEAC5;
    padding: 12px 25px;
    border-radius: 12px;
    font-weight: 600;
    box-shadow: 0 5px 15px rgba(96, 63, 38, 0.3);
}

/* کارت‌های آماری */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 2fr));
    gap: 50px;
    margin-bottom: 60px;
}

.stat-card {
    background: #FFEAC5;
    border-radius: 20px;
    padding: 25px;
    display: flex;
    align-items: center;
    gap: 20px;
    position: relative;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    border: 2px solid transparent;
    cursor: pointer;
}



.stat-card:hover {
    transform: translateY(-8px) scale(1.02);
    border-color: #603F26;
    box-shadow: 0 20px 40px rgba(96, 63, 38, 0.25);
}

.stat-icon {
    font-size: 2.5em;
    width: 70px;
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(96, 63, 38, 0.1);
    border-radius: 15px;
    flex-shrink: 0;
    position: relative;
    z-index: 2;
}

.stat-info {
    position: relative;
    z-index: 2;
}

.stat-info h3 {
    font-size: 2.2em;
    color: #603F26;
    margin-bottom: 5px;
    font-weight: 900;
    line-height: 1;
}

.stat-info p {
    color: #825B32;
    font-size: 0.95em;
    font-weight: 500;
}

.stat-bg {
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200px;
    height: 200px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(96, 63, 38, 0.08) 0%, transparent 70%);
    transition: transform 0.4s;
}

.stat-card:hover .stat-bg {
    transform: scale(1.5);
}

/* رنگ‌های خاص */
.stat-products .stat-icon { background: rgba(96, 63, 38, 0.15); }
.stat-orders .stat-icon { background: rgba(40, 167, 69, 0.15); }
.stat-users .stat-icon { background: rgba(0, 123, 255, 0.15); }
.stat-categories .stat-icon { background: rgba(255, 193, 7, 0.15); }
.stat-revenue .stat-icon { background: rgba(40, 167, 69, 0.2); }
.stat-pending .stat-icon { background: rgba(255, 87, 34, 0.15); }

/* بخش‌ها */
.section-heading {
    color: #603F26;
    font-size: 1.5em;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 10px;
}

/* دکمه‌های سریع */
.quick-actions {
    margin-bottom: 60px;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 20px;
}

.action-card {
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(10px);
    border: 2px solid #603F26;
    border-radius: 20px;
    padding: 30px 20px;
    text-decoration: none;
    text-align: center;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.action-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(96, 63, 38, 0.1), transparent);
    transition: right 0.6s;
}

.action-card:hover::before {
    right: 100%;
}

.action-card:hover {
    transform: translateY(-8px);
    background: #FFEAC5;
    box-shadow: 0 20px 40px rgba(96, 63, 38, 0.2);
}

.action-primary {
    background: linear-gradient(135deg, #603F26, #825B32);
    border-color: #603F26;
}

.action-primary .action-icon,
.action-primary .action-text {
    color: #FFEAC5;
}

.action-icon {
    font-size: 2.5em;
    margin-bottom: 12px;
    display: block;
    transition: transform 0.3s;
}

.action-card:hover .action-icon {
    transform: scale(1.2) rotate(5deg);
}

.action-text {
    color: #603F26;
    font-weight: 600;
    font-size: 1.05em;
}

/* سفارشات */
.recent-orders {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
}

.orders-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    flex-wrap: wrap;
    gap: 15px;
}

.view-all {
    color: #603F26;
    font-weight: 600;
    text-decoration: none;
    padding: 8px 16px;
    border-radius: 10px;
    background: #FFEAC5;
    transition: all 0.3s;
}

.view-all:hover {
    background: #603F26;
    color: #FFEAC5;
    transform: translateX(-5px);
}

.orders-table-wrapper {
    overflow-x: auto;
}

.orders-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 600px;
}

.orders-table thead {
    background: linear-gradient(135deg, #603F26, #825B32);
}

.orders-table th {
    padding: 15px 12px;
    color: #FFEAC5;
    font-weight: 600;
    text-align: right;
    font-size: 0.95em;
}

.orders-table th:first-child { border-radius: 0 12px 0 0; }
.orders-table th:last-child { border-radius: 12px 0 0 0; }

.orders-table tbody tr {
    border-bottom: 1px solid #f0e0c8;
    transition: background 0.3s;
}

.orders-table tbody tr:hover {
    background: #FFEAC5;
}

.orders-table td {
    padding: 15px 12px;
    color: #2C1C10;
    font-size: 0.95em;
}

/* وضعیت */
.status-badge {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.85em;
    font-weight: 600;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-processing {
    background: #cce5ff;
    color: #004085;
}

.status-shipped {
    background: #d1ecf1;
    color: #0c5460;
}

.status-delivered {
    background: #d4edda;
    color: #155724;
}

.status-cancelled {
    background: #f8d7da;
    color: #721c24;
}

.table-btn {
    display: inline-block;
    background: #603F26;
    color: #FFEAC5;
    padding: 6px 12px;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.3s;
}

.table-btn:hover {
    background: #825B32;
    transform: scale(1.1);
}

/* خالی */
.empty-orders {
    text-align: center;
    padding: 60px 20px;
    color: #825B32;
}

.empty-icon {
    font-size: 4em;
    margin-bottom: 15px;
    opacity: 0.5;
}

/* موبایل */
@media (max-width: 768px) {
    .admin-title {
        font-size: 1.8em;
    }
    
    .admin-date {
        padding: 10px 18px;
        font-size: 0.9em;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    .stat-card {
        padding: 18px 15px;
        flex-direction: column;
        text-align: center;
        gap: 10px;
    }
    
    .stat-icon {
        width: 55px;
        height: 55px;
        font-size: 1.8em;
    }
    
    .stat-info h3 {
        font-size: 1.6em;
    }
    
    .stat-info p {
        font-size: 0.85em;
    }
    
    .actions-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    .action-card {
        padding: 20px 12px;
    }
    
    .action-icon {
        font-size: 2em;
    }
    
    .action-text {
        font-size: 0.9em;
    }
    
    .recent-orders {
        padding: 20px 15px;
    }
    
    .section-heading {
        font-size: 1.2em;
    }
    
    .orders-table th,
    .orders-table td {
        padding: 10px 8px;
        font-size: 0.85em;
    }
}

/* ============================================
   نمودار فروش داشبورد
   ============================================ */

.sales-chart-section {
    background: white;
    border-radius: 20px;
    padding: 30px;
    margin-bottom: 60px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    animation: fadeInUp 0.6s ease-out;
}

.chart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 15px;
}

.chart-header .section-heading {
    margin-bottom: 0;
}

.chart-total {
    background: linear-gradient(135deg, #603F26, #825B32);
    color: #FFEAC5;
    padding: 10px 20px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.9em;
    box-shadow: 0 5px 15px rgba(96, 63, 38, 0.2);
}

.chart-total-label {
    opacity: 0.85;
    font-weight: 500;
}

.chart-total-value {
    font-weight: 700;
    font-size: 1.1em;
}

/* کانتینر نمودار */
.chart-container {
    width: 100%;
    overflow-x: auto;
    overflow-y: visible;      /* ← اجازه بده Tooltip بیاد بالا */
    padding: 120px 10px 10px;  /* ← فضای بالا برای Tooltip */
    scrollbar-width: thin;
}

.chart-bars {
    display: flex;
    gap: 15px;
    align-items: flex-end;
    min-height: 280px;
    padding: 0 10px;
    min-width: 600px;
    position: relative;
}

/* هر ستون */
.chart-bar-wrapper {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    position: relative;
    opacity: 0;
    animation: barFadeIn 0.6s ease-out forwards;
    animation-delay: var(--delay);
}

@keyframes barFadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* مقدار بالای ستون */
.chart-bar-value {
    font-size: 0.75em;
    color: #825B32;
    font-weight: 600;
    white-space: nowrap;
}

/* ستون */
.chart-bar {
    width: 100%;
    max-width: 60px;
    height: 180px;
    background: #f0e0c8;
    border-radius: 10px 10px 0 0;
    position: relative;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.3s;
}

.chart-bar-wrapper:hover .chart-bar {
    transform: scaleY(1.05);
    box-shadow: 0 10px 25px rgba(96, 63, 38, 0.2);
}

/* پرشدگی ستون */
.chart-bar-fill {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(180deg, #825B32, #603F26);
    border-radius: 10px 10px 0 0;
    transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    height: 0;
    animation: barGrow 1s ease-out forwards;
    animation-delay: calc(var(--delay) + 0.3s);
    position: relative;
}

.chart-bar-fill::after {
    content: '';
    position: absolute;
    top: 5px;
    left: 10%;
    right: 10%;
    height: 8px;
    background: rgba(255, 234, 197, 0.3);
    border-radius: 50%;
    filter: blur(2px);
}

@keyframes barGrow {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

/* اگه فروش صفر باشه */
.chart-bar-fill[style*="height: 0%"],
.chart-bar-fill[style*="height: 0%;"] {
    background: #d0c0a8;
}

/* برچسب روز */
.chart-bar-label {
    text-align: center;
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.day-name {
    font-size: 0.8em;
    color: #603F26;
    font-weight: 600;
}

.day-date {
    font-size: 0.7em;
    color: #825B32;
    opacity: 0.7;
}

/* Tooltip */
/* Tooltip — بالای همه */
.chart-bar-tooltip {
    position: absolute;
    bottom: calc(100% + 10px);
    left: 50%;
    transform: translateX(-50%) translateY(5px);
    background: #3D2818;
    color: #FFEAC5;
    padding: 12px 18px;
    border-radius: 12px;
    font-size: 0.8em;
    white-space: nowrap;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 9999;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4), 0 5px 15px rgba(0, 0, 0, 0.2);
    text-align: center;
    pointer-events: none;
    border: 2px solid #825B32;
}

.chart-bar-tooltip::after {
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    border: 8px solid transparent;
    border-top-color: #3D2818;
    margin-top: -2px;
}

/* نمایش با Hover */
.chart-bar-wrapper:hover .chart-bar-tooltip {
    opacity: 1;
    visibility: visible;
    transform: translateX(-50%) translateY(0);
}

/* Tooltip — محتوای داخل */
.tooltip-date {
    font-weight: 700;
    margin-bottom: 6px;
    color: #FFD700;
    font-size: 1.05em;
    border-bottom: 1px dashed rgba(255, 234, 197, 0.3);
    padding-bottom: 4px;
}

.tooltip-sales {
    color: #fff;
    font-weight: 600;
    margin-bottom: 3px;
    font-size: 1em;
}

.tooltip-count {
    color: #FFEAC5;
    opacity: 0.85;
    font-size: 0.9em;
}

/* ستون hover شده — بالاتر از بقیه */
.chart-bar-wrapper:hover {
    z-index: 100;
}

/* ✨ یه هاله کوچیک دور Tooltip */
.chart-bar-tooltip::before {
    content: '';
    position: absolute;
    inset: -4px;
    border-radius: 14px;
    background: linear-gradient(135deg, #FFD700, #825B32, #FFD700);
    z-index: -1;
    opacity: 0.5;
    filter: blur(6px);
}

/* ============================================
   موبایل
   ============================================ */

@media (max-width: 768px) {
    .sales-chart-section {
        padding: 20px 15px;
        margin-bottom: 40px;
        border-radius: 15px;
    }
    
    .chart-header {
        flex-direction: column;
        align-items: stretch;
        gap: 12px;
        margin-bottom: 20px;
    }
    
    .chart-header .section-heading {
        font-size: 1.2em;
    }
    
    .chart-total {
        justify-content: center;
        padding: 8px 15px;
        font-size: 0.85em;
    }
    
    .chart-bars {
        gap: 8px;
        min-height: 220px;
        padding: 15px 5px 0;
    }
    
    .chart-bar {
        height: 140px;
        max-width: 40px;
    }
    
    .chart-bar-value {
        font-size: 0.65em;
    }
    
    .day-name {
        font-size: 0.7em;
    }
    
    .day-date {
        font-size: 0.6em;
    }
    
    .chart-bar-tooltip {
        font-size: 0.75em;
        padding: 8px 12px;
    }
}

@media (max-width: 480px) {
    .chart-bar {
        height: 120px;
        max-width: 35px;
    }
    
    .chart-bars {
        gap: 6px;
        min-height: 200px;
    }
    
    .chart-bar-value {
        display: none;
    }
}

</style>

@endsection