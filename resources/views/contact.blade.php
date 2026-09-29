@extends('layouts.app')

@section('title', 'تماس با ما | رایکا')
@section('description', 'راه‌های ارتباط با فروشگاه شکلات رایکا - تلفن، ایمیل، آدرس')

@section('content')

<nav class="breadcrumb">
    <a href="{{ route('home') }}" class="breadcrumb-item">🏠 خانه</a>
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">📞 تماس با ما</span>
</nav>

    <div style="max-width: 800px; margin: 0 auto; color: #2C1C10;">
        <h1 style="text-align: center; color: #603F26; margin-bottom: 30px;">
            تماس با ما
        </h1>
        
        <div style="background: rgba(255, 255, 255, 0.5); padding: 30px; border-radius: 15px; margin-bottom: 20px;">
            <h3 style="color: #603F26; margin-bottom: 10px;">📍 شعبه اصفهان:</h3>
            <p>اصفهان، خیابان حکیم نظامی، روبروی بانک صادرات</p>
        </div>
        
        <div style="background: rgba(255, 255, 255, 0.5); padding: 30px; border-radius: 15px; margin-bottom: 20px;">
            <h3 style="color: #603F26; margin-bottom: 10px;">📍 شعبه تهران:</h3>
            <p>تهران، بلوار میرداماد، بازار بزرگ میرداماد</p>
        </div>
        
        <div style="background: rgba(255, 255, 255, 0.5); padding: 30px; border-radius: 15px; margin-bottom: 20px;">
            <h3 style="color: #603F26; margin-bottom: 10px;">📞 تلفن امور مشتریان:</h3>
            <p>۰۳۱-۰۰۲۵۶</p>
            <p>۰۹۹۲۰۹۹۸۷۸۷</p>
            <p style="font-size: 0.9em; margin-top: 10px;">
                شنبه تا چهارشنبه: ۹:۰۰ تا ۲۲:۰۰<br>
                پنجشنبه: ۹:۰۰ تا ۱۹:۰۰
            </p>
        </div>
        
        <div style="background: rgba(255, 255, 255, 0.5); padding: 30px; border-radius: 15px;">
            <h3 style="color: #603F26; margin-bottom: 10px;">✉️ ایمیل:</h3>
            <p>raikaShop@gmail.com</p>
        </div>
    </div>
@endsection