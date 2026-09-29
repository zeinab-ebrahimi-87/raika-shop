@extends('layouts.app')

@section('title', 'تکمیل خرید | رایکا')

@section('content')

<nav class="breadcrumb">
    <a href="{{ route('home') }}" class="breadcrumb-item">🏠 خانه</a>
    <span class="breadcrumb-separator">›</span>
    <a href="{{ route('cart.index') }}" class="breadcrumb-item">🛒 سبد خرید</a>
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">📦 تکمیل خرید</span>
</nav>

<div style="max-width: 1100px; margin: 0 auto;">
    <h1 style="text-align: center; color: #603F26; margin-bottom: 30px;">📦 تکمیل خرید</h1>

    @if($errors->any())
        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
            @foreach($errors->all() as $error)
                <p>⚠ {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 30px;">
        
        {{-- فرم اطلاعات --}}
        <form method="POST" action="{{ route('order.store') }}" style="background: rgba(255, 255, 255, 0.9); padding: 30px; border-radius: 20px;">
            @csrf
            <h2 style="color: #603F26; margin-bottom: 20px;">اطلاعات ارسال</h2>
            
            <div class="form-group">
                <label>نام و نام خانوادگی *</label>
                <input type="text" name="name" value="{{ old('name', Auth::user()->name ?? '') }}" required>
            </div>

            <div class="form-group">
                <label>شماره تماس *</label>
                <input type="text" name="phone" value="{{ old('phone') }}" required>
            </div>

            <div class="form-group">
                <label>ایمیل</label>
                <input type="email" name="email" value="{{ old('email', Auth::user()->email ?? '') }}">
            </div>

            <div class="form-group">
                <label>شهر *</label>
                <input type="text" name="city" value="{{ old('city') }}" required>
            </div>

            <div class="form-group">
                <label>آدرس کامل *</label>
                <textarea name="address" rows="3" required>{{ old('address') }}</textarea>
            </div>

            <div class="form-group">
                <label>کد پستی *</label>
                <input type="text" name="postal_code" value="{{ old('postal_code') }}" required>
            </div>

            <div class="form-group">
                <label>توضیحات (اختیاری)</label>
                <textarea name="notes" rows="2">{{ old('notes') }}</textarea>
            </div>

            <button type="submit" style="width: 100%; background: #28a745; color: white; border: none; padding: 18px; border-radius: 12px; font-size: 1.1em; font-weight: bold; cursor: pointer; font-family: Tahoma;">
                ✅ ثبت سفارش
            </button>
        </form>

        {{-- خلاصه سفارش --}}
        <div style="background: #603F26; color: white; padding: 30px; border-radius: 20px; height: fit-content;">
            <h2 style="color: #FFEAC5; margin-bottom: 20px;">خلاصه سفارش</h2>
            
            @foreach($cart as $item)
                <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.2);">
                    <span>{{ $item['name'] }} × {{ $item['qty'] }}</span>
                    <span>{{ number_format($item['price'] * $item['qty']) }} تومان</span>
                </div>
            @endforeach
            
            <div style="margin-top: 20px; padding-top: 20px; border-top: 2px solid #FFEAC5; font-size: 1.3em;">
                <strong>جمع کل: {{ number_format($total) }} تومان</strong>
            </div>
        </div>
    </div>
</div>

<style>
    .form-group { margin-bottom: 18px; }
    .form-group label { display: block; color: #603F26; margin-bottom: 8px; font-weight: bold; }
    .form-group input, .form-group textarea {
        width: 100%;
        padding: 12px;
        border: 2px solid #825B32;
        border-radius: 10px;
        font-family: Tahoma, sans-serif;
        font-size: 1em;
        box-sizing: border-box;
    }
    .form-group input:focus, .form-group textarea:focus {
        outline: none;
        border-color: #603F26;
        box-shadow: 0 0 0 3px rgba(96, 63, 38, 0.2);
    }
    @media (max-width: 768px) {
        div[style*="grid-template-columns: 1.5fr 1fr"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>

@endsection