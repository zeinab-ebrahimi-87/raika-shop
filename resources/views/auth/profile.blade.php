@extends('layouts.app')

@section('title', 'پروفایل | رایکا')

@section('content')

<nav class="breadcrumb">
    <a href="{{ route('home') }}" class="breadcrumb-item">🏠 خانه</a>
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">👤 پروفایل</span>
</nav>


<div style="max-width: 700px; margin: 40px auto;">
    <div style="background: rgba(255, 255, 255, 0.9); padding: 40px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
        <h1 style="color: #603F26; margin-bottom: 30px;">پروفایل من</h1>
        
        <div style="background: #FFEAC5; padding: 20px; border-radius: 12px; margin-bottom: 20px;">
            <p style="color: #825B32; margin-bottom: 8px;">نام:</p>
            <p style="color: #603F26; font-weight: bold; font-size: 1.2em;">{{ $user->name }}</p>
        </div>
        
        <div style="background: #FFEAC5; padding: 20px; border-radius: 12px; margin-bottom: 20px;">
            <p style="color: #825B32; margin-bottom: 8px;">ایمیل:</p>
            <p style="color: #603F26; font-weight: bold; font-size: 1.2em;">{{ $user->email }}</p>
        </div>
        
        <div style="background: #FFEAC5; padding: 20px; border-radius: 12px; margin-bottom: 30px;">
            <p style="color: #825B32; margin-bottom: 8px;">تاریخ عضویت:</p>
            <p style="color: #603F26; font-weight: bold;">{{ $user->created_at->format('Y/m/d') }}</p>
        </div>
        
        <form method="POST" action="/logout">
            @csrf
            <button type="submit" style="background: #d32f2f; color: white; border: none; padding: 15px 30px; border-radius: 10px; font-size: 1em; font-weight: bold; cursor: pointer; ">
                خروج از حساب
            </button>
        </form>
    </div>
</div>
@endsection