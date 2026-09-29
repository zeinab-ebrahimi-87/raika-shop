@extends('layouts.app')

@section('title', 'خطای سرور | رایکا')

@section('content')
<div style="text-align: center; padding: 60px 20px; max-width: 600px; margin: 0 auto;">
    <div style="font-size: 8em; margin-bottom: 20px; animation: pulse 2s infinite;">⚠️</div>
    <h1 style="color: #603F26; font-size: 3em; margin-bottom: 20px;">۵۰۰</h1>
    <h2 style="color: #825B32; margin-bottom: 30px;">مشکلی پیش اومد!</h2>
    <p style="color: #4a2f1c; margin-bottom: 40px; line-height: 1.8;">
        سرور با خطا مواجه شد. لطفاً بعداً دوباره تلاش کن.
    </p>
    <a href="{{ route('home') }}" class="hero-btn primary" style="display: inline-block;">🏠 بازگشت به خانه</a>
</div>
@endsection