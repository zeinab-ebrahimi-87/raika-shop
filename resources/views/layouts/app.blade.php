<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'رایکا | فروشگاه شکلات')</title>
    
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="icon" href="{{ asset('images/placeholder.jpg') }}">
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">

    <!-- SEO -->
    <meta name="description" content="@yield('description', 'فروشگاه شکلات رایکا - با کیفیت‌ترین شکلات‌های ایرانی. شکلات وانیلی، تلخ، هدیه، فندقی، شیری، سیگاری. ارسال سریع به سراسر کشور.')">
    <meta name="keywords" content="@yield('keywords', 'شکلات, شکلات وانیلی, شکلات تلخ, شکلات هدیه, شکلات فندقی, شکلات شیری, شکلات سیگاری, خرید شکلات آنلاین, فروشگاه شکلات, رایکا')">
    <meta name="author" content="رایکا">
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#603F26">
    <meta name="language" content="Persian">
    <meta name="revisit-after" content="7 days">

    <!-- Open Graph برای اشتراک‌گذاری -->
    <meta property="og:title" content="رایکا | فروشگاه شکلات">
    <meta property="og:description" content="فروشگاه شکلات رایکا - با کیفیت‌ترین شکلات‌های ایرانی">
    <meta property="og:type" content="website">


    <!-- جلوگیری از محتوای تکراری -->
    <link rel="canonical" href="{{ url()->current() }}">

</head>
<body>

    @include('partials.header')
    
    <main>
        @yield('content')
    </main>

    {{-- Toast Notifications --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('success', 'موفق!', @json(session('success')));
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('error', 'خطا!', @json(session('error')));
            });
        </script>
    @endif

    @if(session('warning'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('warning', 'توجه!', @json(session('warning')));
            });
        </script>
    @endif

    @if(session('info'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('info', 'اطلاع', @json(session('info')));
            });
        </script>
    @endif
    
    <!-- خطاهای Validation -->
    @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                @foreach($errors->all() as $error)
                    showToast('error', 'خطا در فرم', @json($error));
                @endforeach
            });
        </script>
    @endif

    @include('partials.footer')
    
    <button href="#" class="back-to-top" id="backToTop" aria-label="بازگشت به بالا">
    ↑
    </button>
    
    <script src="{{ asset('js/main.js') }}"></script>

    

</body>
</html>