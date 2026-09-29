@extends('layouts.app')

@section('title', 'محصولات | رایکا')
@section('description', 'لیست کامل محصولات فروشگاه شکلات رایکا - شکلات وانیلی، تلخ، هدیه، فندقی و...')

@section('content')

<!--  Breadcrumb  -->
<nav class="breadcrumb">
    <a href="{{ route('home') }}" class="breadcrumb-item">
        🏠 خانه
    </a>
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">
        📦 محصولات
    </span>
</nav>

<div class="products-page">
    
    <!-- سایدبار -->
    <aside class="products-sidebar">
        
        <!-- دسته‌بندی‌ها -->
        <div class="sidebar-card">
            <h3 class="sidebar-title">📂 دسته‌بندی‌ها</h3>
            <div class="category-list">
                <a href="{{ route('products.index') }}" 
                class="category-item {{ !request('category') ? 'active' : '' }}">
                    <span>همه محصولات</span>
                    <span class="count">{{ $categories->sum('products_count') }}</span>
                    <span class="cocoa-particles"></span>
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('products.index', ['category' => $cat->slug]) }}" 
                    class="category-item {{ request('category') == $cat->slug ? 'active' : '' }}">
                        <span>{{ $cat->name }}</span>
                        <span class="count">{{ $cat->products_count }}</span>
                        <span class="cocoa-particles"></span>
                    </a>
                @endforeach
            </div>
        </div>
        
        <!-- فیلترها -->
        <div class="sidebar-card">
            <h3 class="sidebar-title">🎚 فیلترها</h3>
            
            <form method="GET" action="{{ route('products.index') }}" id="filterForm">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                
                <div class="filter-group">
                    <label class="filter-label">مرتب‌سازی:</label>
                    <select name="sort" class="filter-select" onchange="this.form.submit()">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>جدیدترین</option>
                        <option value="cheap" {{ request('sort') == 'cheap' ? 'selected' : '' }}>ارزان‌ترین</option>
                        <option value="expensive" {{ request('sort') == 'expensive' ? 'selected' : '' }}>گران‌ترین</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>محبوب‌ترین</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="switch-filter">
                        <input type="checkbox" name="in_stock" value="1" 
                               {{ request('in_stock') ? 'checked' : '' }}
                               onchange="this.form.submit()">
                        <span class="switch-slider"></span>
                        <span>فقط موجود</span>
                    </label>
                </div>
                
                <div class="filter-group">
                    <label class="switch-filter">
                        <input type="checkbox" name="featured" value="1" 
                               {{ request('featured') ? 'checked' : '' }}
                               onchange="this.form.submit()">
                        <span class="switch-slider"></span>
                        <span>فقط ویژه ⭐</span>
                    </label>
                </div>
                
                @if(request()->hasAny(['sort', 'in_stock', 'featured', 'category']))
                    <a href="{{ route('products.index') }}" class="clear-filters">
                        ✕ پاک کردن فیلترها
                    </a>
                @endif
            </form>
        </div>
        
    </aside>
    
    <!-- محتوای اصلی -->
    <main class="products-main">
        
        <div class="products-header">
            <h1 class="products-title">
                @if(request('category'))
                    @php $currentCat = $categories->where('slug', request('category'))->first(); @endphp
                    {{ $currentCat->name ?? 'محصولات' }}
                @else
                    همه محصولات
                @endif
            </h1>

            @if(($product->reviews_count ?? 0) > 0)
            <div class="product-rating">
                    <span class="stars">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= round($product->average_rating))
                                ⭐
                            @else
                                ☆
                            @endif
                        @endfor
                    </span>
                    <span class="rating-text">{{ number_format($product->average_rating, 1) }} ({{ $product->reviews_count }})</span>
                </div>
            @endif


            <p class="products-count">{{ $products->count() }} محصول</p>
        </div>
        
        @if($products->count() > 0)
            <div class="product-grid">
                @foreach($products as $product)
                    <div class="product-card">
                        <div class="product-image">
                            @if($product->image && file_exists(public_path('images/products/' . $product->image)))
                                <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}">
                            @else
                                <img src="{{ asset('images/placeholder.jpg') }}" alt="{{ $product->name }}">
                            @endif
                            
                            @if($product->stock <= 0)
                                <span class="out-of-stock-badge">❌ ناموجود</span>
                            @endif
                            
                            @if($product->is_featured && $product->stock > 0)
                                <span class="featured-badge">⭐ ویژه</span>
                            @endif
                        </div>
                        <h3 class="product-title">{{ $product->name }}</h3>
                        <p class="product-price">
                            @if($product->variants->count() > 0)
                                از {{ number_format($product->display_price) }} تومان
                            @else
                                {{ number_format($product->price) }} تومان
                            @endif
                        </p>
                        <a href="{{ route('products.show', $product->slug) }}" class="product-btn">
                            مشاهده محصول
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-products">
                <div class="empty-icon">🔍</div>
                <h2>محصولی پیدا نشد</h2>
                <p>فیلترها رو تغییر بده یا همه رو ببین</p>
                <a href="{{ route('products.index') }}" class="btn-clear">مشاهده همه محصولات</a>
            </div>
        @endif
        
    </main>
    
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var sidebarCards = document.querySelectorAll('.products-sidebar .sidebar-card');
    
    sidebarCards.forEach(function(card) {
        var title = card.querySelector('.sidebar-title');
        
        if (title) {
            title.addEventListener('click', function(e) {
                // فقط تو موبایل کار کنه
                if (window.innerWidth > 992) return;
                
                e.stopPropagation();
                
                // بستن بقیه
                sidebarCards.forEach(function(otherCard) {
                    if (otherCard !== card) {
                        otherCard.classList.remove('open');
                    }
                });
                
                // Toggle این کارت
                card.classList.toggle('open');
            });
        }
    });
    
    // کلیک بیرون → بستن همه
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.sidebar-card')) {
            sidebarCards.forEach(function(card) {
                card.classList.remove('open');
            });
        }
    });
});
</script>

@endsection