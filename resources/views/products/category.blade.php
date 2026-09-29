@extends('layouts.app')

@section('title', $category->name . ' | رایکا')

@section('content')

<nav class="breadcrumb">
    <a href="{{ route('home') }}" class="breadcrumb-item">🏠 خانه</a>
    <span class="breadcrumb-separator">›</span>
    <a href="{{ route('products.index') }}" class="breadcrumb-item">📦 محصولات</a>
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">{{ $category->name }}</span>
</nav>

<div style="text-align: center; margin-bottom: 40px;">
    <a href="{{ route('products.index') }}" style="color: #825B32; text-decoration: none;">
        ← همه محصولات
    </a>
    <h1 style="color: #603F26; font-size: 2.5em; margin-top: 15px;">{{ $category->name }}</h1>
    <p style="color: #825B32;">{{ $products->count() }} محصول</p>
</div>

@if($products->count() > 0)
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 25px;">
        @foreach($products as $product)
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
@else
    <p style="text-align: center; color: #825B32;">محصولی تو این دسته نیست.</p>
@endif

<style>
    .product-card {
        background: #543721;
        border: 2px solid #603F26;
        border-radius: 20px;
        overflow: hidden;
        transition: 0.3s;
        text-align: center;
    }
    .product-card:hover { transform: translateY(-8px); box-shadow: 0 10px 20px rgba(0,0,0,0.3); }
    .product-image { background: #FFEAC5; height: 180px; overflow: hidden; }
    .product-image img { width: 100%; height: 100%; object-fit: cover; }
    .product-title { color: #FCF3E3; font-size: 1.1em; margin: 15px 10px 8px; min-height: 50px; }
    .product-price { color: #F8E1B7; font-weight: bold; font-size: 1.1em; margin-bottom: 15px; }
    .product-btn { display: block; background: #603F26; color: #F8E1B7; padding: 12px; text-decoration: none; font-weight: bold; transition: 0.3s; }
    .product-btn:hover { background: #F8E1B7; color: #603F26; }
</style>

@endsection