@extends('layouts.app')

@section('title', 'مدیریت محصولات | رایکا')

@section('content')

<a href="{{ route('admin.dashboard') }}" class="admin-back-btn">
    <span class="arrow">←</span>
    بازگشت به داشبورد
</a>

<div class="admin-products">
    
    <!-- هدر -->
    <div class="page-header">
        <div>
            <h1>📦 مدیریت محصولات</h1>
            <p class="subtitle">مجموع: {{ $products->total() }} محصول</p>
        </div>

        <div class="admin-search">
            <input type="text" 
                id="adminProductSearch" 
                placeholder="🔍 جستجوی محصولات..." 
                autocomplete="off">
            <div class="admin-search-results" id="adminProductResults"></div>
        </div>


        <a href="{{ route('admin.products.create') }}" class="btn-add">
            ➕ افزودن محصول
        </a>

        
    </div>
    
    
    <!-- جدول محصولات -->
    <div class="products-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>تصویر</th>
                    <th>نام محصول</th>
                    <th>دسته‌بندی</th>
                    <th>قیمت</th>
                    <th>موجودی</th>
                    <th>وضعیت</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>
                        <div class="product-thumb">
                            @if($product->image && file_exists(public_path('images/products/' . $product->image)))
                                <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}">
                            @else
                                <img src="{{ asset('images/placeholder.jpg') }}" alt="{{ $product->name }}">
                            @endif
                        </div>
                    </td>
                    <td>
                        <strong>{{ $product->name }}</strong>
                        <div class="slug">{{ $product->slug }}</div>
                    </td>
                    <td>{{ $product->category->name ?? '-' }}</td>
                    <td>{{ number_format($product->price) }} ت</td>
                    <td>
                        @if($product->stock > 10)
                            <span class="stock-badge stock-good">{{ $product->stock }}</span>
                        @elseif($product->stock > 0)
                            <span class="stock-badge stock-low">{{ $product->stock }}</span>
                        @else
                            <span class="stock-badge stock-out">ناموجود</span>
                        @endif
                    </td>
                    <td>
                        @if($product->is_active)
                            <span class="status-badge status-active">فعال</span>
                        @else
                            <span class="status-badge status-inactive">غیرفعال</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn-action btn-edit" title="ویرایش">
                                ✏️
                            </a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}" onsubmit="return confirm('مطمئنی میخوای حذف کنی؟')" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" title="حذف">
                                    🗑
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="empty-row">
                        <div class="empty-state">
                            <div class="empty-icon">📭</div>
                            <p>هنوز محصولی اضافه نکردی</p>
                            <a href="{{ route('admin.products.create') }}" class="btn-add">➕ افزودن اولین محصول</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- صفحه‌بندی -->
    @if($products->hasPages())
        <div class="pagination-wrapper">
            {{ $products->links() }}
        </div>
    @endif
    
</div>

<style>
.admin-products {
    max-width: 1300px;
    margin: 0 auto;
    animation: fadeInUp 0.6s ease-out;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 20px;
}

.page-header h1 {
    font-size: 2.2em;
    color: #603F26;
    margin-bottom: 5px;
}

.subtitle {
    color: #825B32;
    font-size: 0.95em;
}

.btn-add {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    padding: 14px 30px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
    box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
    display: inline-block;
}

.btn-add:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(40, 167, 69, 0.4);
}

.alert-success {
    background: #d4edda;
    color: #155724;
    padding: 15px 20px;
    border-radius: 12px;
    margin-bottom: 25px;
    border-right: 4px solid #28a745;
    animation: slideDown 0.4s ease-out;
}

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

.products-table-wrapper {
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
    padding: 18px 15px;
    color: #FFEAC5;
    font-weight: 600;
    text-align: right;
    font-size: 0.95em;
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

.product-thumb {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    overflow: hidden;
    background: #FFEAC5;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

.product-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.slug {
    color: #825B32;
    font-size: 0.8em;
    margin-top: 3px;
    font-family: monospace;
}

.stock-badge {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.85em;
}

.stock-good {
    background: #d4edda;
    color: #155724;
}

.stock-low {
    background: #fff3cd;
    color: #856404;
}

.stock-out {
    background: #f8d7da;
    color: #721c24;
}

.status-badge {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.85em;
    font-weight: 600;
}

.status-active {
    background: #d4edda;
    color: #155724;
}

.status-inactive {
    background: #f8d7da;
    color: #721c24;
}

.action-buttons {
    display: flex;
    gap: 8px;
    justify-content: flex-start;
}

.btn-action {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    transition: all 0.3s;
    text-decoration: none;
}

.btn-edit {
    background: #cce5ff;
    color: #004085;
}

.btn-edit:hover {
    background: #004085;
    color: white;
    transform: scale(1.1) rotate(-5deg);
}

.btn-delete {
    background: #f8d7da;
    color: #721c24;
}

.btn-delete:hover {
    background: #721c24;
    color: white;
    transform: scale(1.1) rotate(5deg);
}

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
    margin-bottom: 20px;
    font-size: 1.1em;
}

.pagination-wrapper {
    margin-top: 30px;
    display: flex;
    justify-content: center;
}

@media (max-width: 768px) {
    .page-header h1 {
        font-size: 1.6em;
    }
    
    .btn-add {
        padding: 12px 20px;
        font-size: 0.9em;
    }
    
    .admin-table {
        font-size: 0.85em;
    }
    
    .admin-table th,
    .admin-table td {
        padding: 10px 8px;
    }
    
    .product-thumb {
        width: 45px;
        height: 45px;
    }
    
    .btn-action {
        width: 32px;
        height: 32px;
        font-size: 14px;
    }
}
</style>


<script>
document.addEventListener('DOMContentLoaded', function() {
    var input = document.getElementById('adminProductSearch');
    var results = document.getElementById('adminProductResults');
    
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
            fetch('/admin/search/products?q=' + encodeURIComponent(q))
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    if (data.length === 0) {
                        results.innerHTML = '<div class="search-empty-mini">محصولی پیدا نشد</div>';
                        results.classList.add('open');
                        return;
                    }
                    
                    var html = '';
                    data.forEach(function(item) {
                        html += '<a href="' + item.edit_url + '" class="search-result-mini">' +
                            '<img src="' + item.image + '" alt="">' +
                            '<div>' +
                            '<div class="result-name">' + item.name + '</div>' +
                            '<div class="result-price">' + item.price + '</div>' +
                            '</div>' +
                            '</a>';
                    });
                    
                    results.innerHTML = html;
                    results.classList.add('open');
                });
        }, 300);
    });
    
    // کلیک بیرون → بستن
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.admin-search')) {
            results.classList.remove('open');
        }
    });
});
</script>


@endsection