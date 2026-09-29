@extends('layouts.app')

@section('title', 'سبد خرید | رایکا')

@section('content')

<nav class="breadcrumb">
    <a href="{{ route('home') }}" class="breadcrumb-item">🏠 خانه</a>
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">🛒 سبد خرید</span>
</nav>

<div style="max-width: 1000px; margin: 0 auto;">
    <h1 style="text-align: center; color: #603F26; margin-bottom: 30px;">🛒 سبد خرید</h1>

    
    @if(session('error'))
        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    @if(count($cart) > 0)
        <div style="background: rgba(255, 255, 255, 0.9); border-radius: 20px; padding: 20px; margin-bottom: 20px;">
            @foreach($cart as $key => $item)
                <div class="cart-item">
                    <div>
                        <h3 style="color: #603F26; margin-bottom: 8px;">{{ $item['name'] }}</h3>
                        <p style="color: #825B32;">{{ number_format($item['price']) }} تومان</p>
                    </div>

                    <form method="POST" action="{{ route('cart.update', $key) }}" style="display: flex; align-items: center; gap: 10px;">
                        @csrf
                        @method('PUT')
                        <input type="number" name="qty" value="{{ $item['qty'] }}" min="1" style="width: 70px; padding: 8px; border-radius: 8px; border: 2px solid #825B32; text-align: center;">
                        <button type="submit" style="background: #825B32; color: white; border: none; padding: 8px 12px; border-radius: 8px; cursor: pointer;">
                            آپدیت
                        </button>
                    </form>

                    <div style="font-weight: bold; color: #603F26; font-size: 1.1em;">
                        {{ number_format($item['price'] * $item['qty']) }} تومان
                    </div>

                    <form method="POST" action="{{ route('cart.remove', $key) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background: #d32f2f; color: white; border: none; padding: 8px 12px; border-radius: 8px; cursor: pointer;">
                            🗑 حذف
                        </button>
                    </form>
                </div>
            @endforeach
        </div>

        <div style="background: #603F26; color: white; border-radius: 20px; padding: 30px; text-align: center;">
            <h2 style="margin-bottom: 20px;">جمع کل: {{ number_format($total) }} تومان</h2>
            
            <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('products.index') }}">
                    <button style="background: #FFEAC5; color: #603F26; border: none; padding: 15px 30px; border-radius: 12px; font-size: 1em; font-weight: bold; cursor: pointer;">
                        ادامه خرید
                    </button>
                </a>
                
                <form method="POST" action="{{ route('cart.clear') }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background: #d32f2f; color: white; border: none; padding: 15px 30px; border-radius: 12px; font-size: 1em; font-weight: bold; cursor: pointer;">
                        خالی کردن سبد
                    </button>
                </form>
                
                <a href="{{ route('order.checkout') }}">
                    <button style="background: #28a745; color: white; border: none; padding: 15px 40px; border-radius: 12px; font-size: 1em; font-weight: bold; cursor: pointer;">
                        ادامه و پرداخت
                    </button>
                </a>
            </div>
        </div>
    @else
        <div style="background: rgba(255, 255, 255, 0.9); border-radius: 20px; padding: 60px 20px; text-align: center;">
            <h2 style="color: #603F26; font-size: 3em; margin-bottom: 20px;">🛒</h2>
            <p style="color: #825B32; font-size: 1.3em; margin-bottom: 30px;">سبد خریدت خالیه!</p>
            <a href="{{ route('products.index') }}">
                <button style="background: #603F26; color: #FFEAC5; border: none; padding: 15px 40px; border-radius: 12px; font-size: 1em; font-weight: bold; cursor: pointer;">
                    مشاهده محصولات
                </button>
            </a>
        </div>
    @endif
</div>

<style>
    .cart-item {
        display: grid;
        grid-template-columns: 2fr 1.5fr 1fr 100px;
        gap: 20px;
        align-items: center;
        padding: 20px;
        border-bottom: 1px solid #e0e0e0;
    }
    .cart-item:last-child {
        border-bottom: none;
    }
    @media (max-width: 768px) {
        .cart-item {
            grid-template-columns: 1fr;
            text-align: center;
        }
    }
</style>

@endsection