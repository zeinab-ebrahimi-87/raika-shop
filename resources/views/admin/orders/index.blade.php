@extends('layouts.app')

@section('title', 'مدیریت سفارشات | رایکا')

@section('content')

<div class="admin-orders-page">
    
    <!-- دکمه بازگشت -->
    <a href="{{ route('admin.dashboard') }}" class="admin-back-btn">
        <span class="arrow">←</span>
        بازگشت به داشبورد
    </a>
    
    <!-- هدر -->
    <div class="page-header">
        <div>
            <h1>🛒 مدیریت سفارشات</h1>
            <p class="subtitle">مجموع: {{ $orders->total() }} سفارش</p>
        </div>
    </div>
    
    
    
    <!-- نوار ابزار: سرچ + فیلتر -->
    <div class="orders-toolbar">
        {{-- سرچ --}}
        <div class="admin-search">
            <input type="text" 
                   id="adminOrderSearch" 
                   placeholder="🔍 جستجوی سفارش (نام، تلفن، شماره)..." 
                   autocomplete="off">
            <div class="admin-search-results" id="adminOrderResults"></div>
        </div>
        
        {{-- فیلتر وضعیت --}}
        <select class="filter-select" id="statusFilter" onchange="filterOrders(this.value)">
            <option value="all">همه وضعیت‌ها</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ در انتظار</option>
            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>🔄 در حال پردازش</option>
            <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>🚚 ارسال شده</option>
            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>✅ تحویل شده</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>❌ لغو شده</option>
        </select>
        
        {{-- مرتب‌سازی --}}
        <select class="filter-select" id="sortFilter" onchange="sortOrders(this.value)">
            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>جدیدترین</option>
            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>قدیمی‌ترین</option>
            <option value="expensive" {{ request('sort') == 'expensive' ? 'selected' : '' }}>گران‌ترین</option>
            <option value="cheap" {{ request('sort') == 'cheap' ? 'selected' : '' }}>ارزان‌ترین</option>
        </select>
    </div>
    
    <!-- جدول سفارشات -->
    <div class="orders-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>مشتری</th>
                    <th>تلفن</th>
                    <th>جمع</th>
                    <th>وضعیت</th>
                    <th>تاریخ</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    @php
                        $statusMap = [
                            'pending' => ['label' => 'در انتظار', 'class' => 'status-pending', 'icon' => '⏳'],
                            'processing' => ['label' => 'در حال پردازش', 'class' => 'status-processing', 'icon' => '🔄'],
                            'shipped' => ['label' => 'ارسال شده', 'class' => 'status-shipped', 'icon' => '🚚'],
                            'delivered' => ['label' => 'تحویل شده', 'class' => 'status-delivered', 'icon' => '✅'],
                            'cancelled' => ['label' => 'لغو شده', 'class' => 'status-cancelled', 'icon' => '❌'],
                        ];
                        $status = $statusMap[$order->status] ?? ['label' => $order->status, 'class' => '', 'icon' => ''];
                    @endphp
                    <tr>
                        <td data-label="#">
                            <strong>#{{ $order->id }}</strong>
                        </td>
                        <td data-label="مشتری">
                            <div class="customer-info">
                                <strong>{{ $order->name }}</strong>
                            </div>
                        </td>
                        <td data-label="تلفن">
                            <a href="tel:{{ $order->phone }}" class="phone-link">
                                {{ $order->phone }}
                            </a>
                        </td>
                        <td data-label="جمع">
                            <strong class="order-total">{{ number_format($order->total) }} ت</strong>
                        </td>
                        <td data-label="وضعیت">
                            <span class="status-badge {{ $status['class'] }}">
                                {{ $status['icon'] }} {{ $status['label'] }}
                            </span>
                        </td>
                        <td data-label="تاریخ">
                            <span class="order-date">{{ $order->created_at->format('Y/m/d') }}</span>
                            <span class="order-time">{{ $order->created_at->format('H:i') }}</span>
                        </td>
                        <td data-label="عملیات">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn-view" title="مشاهده">
                                👁
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="empty-row">
                            <div class="empty-state">
                                <div class="empty-icon">📭</div>
                                <p>هنوز سفارشی ثبت نشده</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- صفحه‌بندی -->
    @if($orders->hasPages())
        <div class="pagination-wrapper">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    @endif
    
</div>


<style>
/* ============================================
   مدیریت سفارشات ادمین
   ============================================ */

.admin-orders-page {
    max-width: 1200px;
    margin: 0 auto;
    animation: fadeInUp 0.6s ease-out;
}

/* هدر */
.admin-orders-page .page-header {
    margin-bottom: 25px;
}

.admin-orders-page .page-header h1 {
    color: #603F26;
    font-size: 2em;
    margin-bottom: 5px;
}

.admin-orders-page .subtitle {
    color: #825B32;
    font-size: 0.95em;
}

.admin-orders-page .alert-success {
    background: #d4edda;
    color: #155724;
    padding: 15px 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    border-right: 4px solid #28a745;
    animation: slideDown 0.4s ease-out;
}

/* ============================================
   نوار ابزار (سرچ + فیلتر)
   ============================================ */

.orders-toolbar {
    display: flex;
    gap: 12px;
    margin-bottom: 20px;
    flex-wrap: wrap;
    align-items: center;
}

.orders-toolbar .admin-search {
    flex: 1;
    min-width: 250px;
    position: relative;
}

.orders-toolbar .filter-select {
    padding: 12px 18px;
    border: 2px solid #f0e0c8;
    border-radius: 12px;
    font-family: 'IRANSans', Tahoma;
    font-size: 0.9em;
    background: white;
    color: #2C1C10;
    cursor: pointer;
    transition: all 0.3s;
    min-width: 160px;
}

.orders-toolbar .filter-select:focus {
    outline: none;
    border-color: #603F26;
    box-shadow: 0 0 0 4px rgba(96, 63, 38, 0.1);
}

/* ============================================
   جدول سفارشات
   ============================================ */

.orders-table-wrapper {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    overflow-x: auto;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 800px;
}

.admin-table thead {
    background: linear-gradient(135deg, #603F26, #825B32);
}

.admin-table th {
    padding: 16px 15px;
    color: #FFEAC5;
    font-weight: 600;
    text-align: right;
    font-size: 0.95em;
    white-space: nowrap;
}

.admin-table tbody tr {
    border-bottom: 1px solid #f0e0c8;
    transition: background 0.3s;
}

.admin-table tbody tr:hover {
    background: #fffbf2;
}

.admin-table td {
    padding: 15px;
    color: #2C1C10;
    font-size: 0.95em;
    vertical-align: middle;
}

.customer-info strong {
    color: #603F26;
}

.phone-link {
    color: #007bff;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s;
}

.phone-link:hover {
    color: #0056b3;
    text-decoration: underline;
}

.order-total {
    color: #28a745;
    font-size: 1.05em;
}

.order-date {
    display: block;
    color: #603F26;
    font-weight: 600;
    font-size: 0.9em;
}

.order-time {
    display: block;
    color: #825B32;
    font-size: 0.8em;
}

/* Badge وضعیت */
.status-badge {
    display: inline-block;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.85em;
    font-weight: 600;
    white-space: nowrap;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffeeba;
}

.status-processing {
    background: #cce5ff;
    color: #004085;
    border: 1px solid #b8daff;
}

.status-shipped {
    background: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
}

.status-delivered {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.status-cancelled {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

/* دکمه مشاهده */
.btn-view {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    background: #cce5ff;
    color: #004085;
    border-radius: 10px;
    text-decoration: none;
    font-size: 16px;
    transition: all 0.3s;
}

.btn-view:hover {
    background: #004085;
    color: white;
    transform: scale(1.1);
}

/* Empty State */
.empty-row {
    padding: 60px 20px !important;
}

.empty-state {
    text-align: center;
}

.empty-icon {
    font-size: 4em;
    margin-bottom: 15px;
    opacity: 0.5;
}

.empty-state p {
    color: #825B32;
    font-size: 1.05em;
}

/* صفحه‌بندی */
.pagination-wrapper {
    margin-top: 25px;
    display: flex;
    justify-content: center;
}

/* ============================================
   موبایل — جدول تبدیل به کارت
   ============================================ */

@media (max-width: 768px) {
    .admin-orders-page .page-header h1 {
        font-size: 1.5em;
    }
    
    /* نوار ابزار — عمودی */
    .orders-toolbar {
        flex-direction: column;
        gap: 10px;
    }
    
    .orders-toolbar .admin-search {
        width: 100%;
        min-width: auto;
    }
    
    .orders-toolbar .filter-select {
        width: 100%;
        min-width: auto;
    }
    
    /* جدول — کارتی */
    .orders-table-wrapper {
        background: transparent;
        box-shadow: none;
        border-radius: 0;
    }
    
    .admin-table {
        min-width: auto;
    }
    
    .admin-table thead {
        display: none;
    }
    
    .admin-table tbody tr {
        display: block;
        background: white;
        border-radius: 15px;
        margin-bottom: 15px;
        padding: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        border: 2px solid transparent;
        transition: all 0.3s;
    }
    
    .admin-table tbody tr:hover {
        border-color: #FFEAC5;
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(96, 63, 38, 0.15);
    }
    
    .admin-table td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px dashed #f0e0c8;
        font-size: 0.9em;
    }
    
    .admin-table td:last-child {
        border-bottom: none;
    }
    
    .admin-table td::before {
        content: attr(data-label);
        font-weight: 700;
        color: #825B32;
        font-size: 0.85em;
        flex-shrink: 0;
        margin-left: 10px;
    }
    
    .admin-table td[data-label="#"] {
        background: #FFEAC5;
        margin: -15px -15px 10px;
        padding: 12px 15px;
        border-radius: 13px 13px 0 0;
        border-bottom: 2px solid #f0e0c8;
        font-size: 1em;
    }
    
    .admin-table td[data-label="#"]::before {
        content: 'سفارش ';
        color: #603F26;
    }
    
    .customer-info {
        text-align: left;
    }
    
    .order-date,
    .order-time {
        text-align: left;
    }
    
    .btn-view {
        width: 42px;
        height: 42px;
        font-size: 1.1em;
    }
}

@media (max-width: 480px) {
    .admin-orders-page .page-header h1 {
        font-size: 1.3em;
    }
    
    .admin-table td {
        font-size: 0.85em;
        padding: 6px 0;
    }
    
    .status-badge {
        font-size: 0.75em;
        padding: 5px 10px;
    }
    
    .order-total {
        font-size: 0.95em;
    }
}

</style>

<script>
// ============================================
// جستجوی ادمین — سفارشات
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    var input = document.getElementById('adminOrderSearch');
    var results = document.getElementById('adminOrderResults');
    
    if (!input) return;
    
    var timeout;
    
    input.addEventListener('input', function() {
        var q = this.value.trim();
        clearTimeout(timeout);
        
        if (q.length < 1) {
            results.innerHTML = '';
            results.classList.remove('open');
            return;
        }
        
        timeout = setTimeout(function() {
            fetch('/admin/search/orders?q=' + encodeURIComponent(q))
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    if (data.length === 0) {
                        results.innerHTML = '<div class="search-empty-mini">سفارشی پیدا نشد</div>';
                        results.classList.add('open');
                        return;
                    }
                    
                    var html = '';
                    data.forEach(function(item) {
                        html += '<a href="' + item.url + '" class="search-result-mini">' +
                            '<div class="result-icon">📦</div>' +
                            '<div>' +
                            '<div class="result-name">#' + item.id + ' - ' + item.name + '</div>' +
                            '<div class="result-price">' + item.phone + ' | ' + item.total + '</div>' +
                            '</div>' +
                            '</a>';
                    });
                    
                    results.innerHTML = html;
                    results.classList.add('open');
                });
        }, 300);
    });
    
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.admin-search')) {
            results.classList.remove('open');
        }
    });
});

// فیلتر وضعیت
function filterOrders(status) {
    var url = new URL(window.location.href);
    if (status === 'all') {
        url.searchParams.delete('status');
    } else {
        url.searchParams.set('status', status);
    }
    window.location.href = url.toString();
}

// مرتب‌سازی
function sortOrders(sort) {
    var url = new URL(window.location.href);
    url.searchParams.set('sort', sort);
    window.location.href = url.toString();
}
</script>

@endsection