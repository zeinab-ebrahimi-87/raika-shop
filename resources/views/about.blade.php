@extends('layouts.app')
@section('title', 'درباره ما | رایکا')
@section('description', 'آشنایی با فروشگاه شکلات رایکا - ۴ سال تجربه در تولید شکلات با کیفیت')

@section('content')

<nav class="breadcrumb">
    <a href="{{ route('home') }}" class="breadcrumb-item">🏠 خانه</a>
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">ℹ️ درباره ما</span>
</nav>

    <div style="max-width: 800px; margin: 0 auto; color: #2C1C10;">
        <h1 style="text-align: center; color: #603F26; margin-bottom: 30px;">
            درباره فروشگاه رایکا
        </h1>
        
        <p style="line-height: 2; margin-bottom: 20px;">
            شکلات فروشی رایکا با بیش از <strong>۴ سال</strong> تجربه در تولید انواع شکلات،
            یکی از معتبرترین فروشگاه‌های آنلاین در سطح کشور است.
        </p>
        
        <p style="line-height: 2; margin-bottom: 20px;">
            فروشگاه شکلات رایکا در سال ۱۳۹۷ در استان اصفهان تأسیس شد. 
            هدف این مجموعه ارائه شکلات مرغوب، با کیفیت و معتبر بود. 
            شعبه دوم رایکا در سال ۱۳۹۹ در تهران تأسیس شد.
        </p>
        
        <h2 style="color: #603F26; margin-top: 30px;">چشم‌انداز آتی سازمان</h2>
        <p style="line-height: 2;">
            رایکا تولید محصولات خود را تنها با استفاده از باکیفیت‌ترین مواد اولیه ادامه می‌دهد
            تا بتواند به عنوان برند برتر در تولید محصولات شکلاتی در ایران شناخته شود.
        </p>
    </div>
@endsection