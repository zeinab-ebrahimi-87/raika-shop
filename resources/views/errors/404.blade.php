@extends('layouts.app')

@section('title', 'صفحه پیدا نشد | رایکا')

@section('content')
<div style="text-align: center; padding: 60px 20px; max-width: 600px; margin: 0 auto;">
    <div style="font-size: 8em; margin-bottom: 20px; animation: pulse 2s infinite;">🔍</div>
    <h1 style="color: #603F26; font-size: 3em; margin-bottom: 20px;">۴۰۴</h1>
    <h2 style="color: #825B32; margin-bottom: 30px;">صفحه‌ای که دنبالش بودی پیدا نشد!</h2>
    <p style="color: #4a2f1c; margin-bottom: 40px; line-height: 1.8;">
        شاید آدرس رو اشتباه وارد کردی یا این صفحه حذف شده.<br>
        ولی نگران نباش، می‌تونی از اینجا ادامه بدی 👇
    </p>
    <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
        <a href="{{ route('home') }}" class="hero-btn primary" style="display: inline-block;">🏠 صفحه اصلی</a>
        <a href="{{ route('products.index') }}" class="hero-btn secondary" style="display: inline-block;">📦 محصولات</a>
    </div>
</div>
@endsection