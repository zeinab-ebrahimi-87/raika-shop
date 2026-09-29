@extends('layouts.app')

@section('title', 'رایکا | فروشگاه شکلات ایرانی')
@section('description', 'فروشگاه شکلات رایکا - با کیفیت‌ترین شکلات‌های ایرانی. ارسال سریع به سراسر کشور. خرید آنلاین شکلات وانیلی، تلخ، هدیه و...')
@section('title', 'رایکا | فروشگاه شکلات ایرانی')

@section('content')

<section class="hero-section">
    <div class="hero-container">
        <div class="hero-text">
            <h1 class="hero-title">
                <span class="title-line">🍫</span>
                <span class="title-line">طعم</span>
                <span class="title-line">شکلات</span>
                <span class="title-line">واقعی</span>
            </h1>
            <p class="hero-description">
                با هر تکه از شکلات‌های رایکا، دنیایی از لذت و خاطرات شیرین را تجربه کنید
            </p>
            <div class="hero-actions">
                <a href="{{ route('products.index') }}" class="btn-hero btn-primary">
                    🛒 مشاهده محصولات
                </a>
                <a href="{{ route('about') }}" class="btn-hero btn-secondary">
                    ℹ️ درباره ما
                </a>
            </div>
        </div>
        
        <div class="hero-chocolate">
            <div class="chocolate-melt">
                <!-- تخته شکلات -->
                <div class="chocolate-bar">
                    <div class="chocolate-grid">
                        <span></span><span></span><span></span>
                        <span></span><span></span><span></span>
                        <span></span><span></span><span></span>
                        <span></span><span></span><span></span>
                        <span></span><span></span><span></span>
                        <span></span><span></span><span></span>
                    </div>
                </div>
                
                <!-- چکه‌های شکلات -->
                <div class="chocolate-drip drip-1"></div>
                <div class="chocolate-drip drip-2"></div>
                <div class="chocolate-drip drip-3"></div>
                
                <!-- استخر شکلات زیرش -->
                <div class="chocolate-pool"></div>
            </div>
        </div>
    </div>
</section>

<section class="features-section">
    <div class="feature-card">
        <div class="feature-icon">🚚</div>
        <h3>ارسال سریع</h3>
        <p>به سراسر ایران</p>
    </div>
    <div class="feature-card">
        <div class="feature-icon">✨</div>
        <h3>کیفیت برتر</h3>
        <p>مواد اولیه اصل</p>
    </div>
    <div class="feature-card">
        <div class="feature-icon">🎁</div>
        <h3>بسته‌بندی زیبا</h3>
        <p>مناسب هدیه</p>
    </div>
    <div class="feature-card">
        <div class="feature-icon">💯</div>
        <h3>رضایت مشتری</h3>
        <p>تضمین کیفیت</p>
    </div>
</section>

<section class="featured-section">
    <h2 class="section-title">🌟 محصولات پرفروش</h2>
    <div class="product-grid">
        @php
            $featuredProducts = \App\Models\Product::where('is_active', true)->take(4)->get();
        @endphp
        @foreach($featuredProducts as $product)
            <div class="product-card">
                <div class="product-image">
                    @if($product->image && file_exists(public_path('images/products/' . $product->image)))
                        <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}">
                    @else
                        <img src="{{ asset('images/placeholder.jpg') }}" alt="{{ $product->name }}">
                    @endif
                </div>
                <h3 class="product-title">{{ $product->name }}</h3>
                <p class="product-price">{{ number_format($product->price) }} تومان</p>
                <a href="{{ route('products.show', $product->slug) }}" class="product-btn">
                    مشاهده محصول
                </a>
            </div>
        @endforeach
    </div>
    
    <div class="featured-cta">
        <a href="{{ route('products.index') }}" class="btn-hero btn-primary">
            مشاهده همه محصولات ←
        </a>
    </div>
</section>

@endsection