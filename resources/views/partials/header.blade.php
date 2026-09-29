<header>
    <div class="nav-container">
        <a href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="رایکا" class="logo-img">
        </a>
        
        <button class="search-toggle" id="searchToggle" type="button" aria-label="جستجو">
            🔍
        </button>


        <button class="hamburger" id="hamburger" aria-label="منو">
            <span></span>
            <span></span>
            <span></span>
        </button>
        
        <div class="nav-menu" id="navMenu">
            <a href="{{ route('home') }}"><button class="btn-menu"><span>🏠 خانه</span></button></a>
            <a href="{{ route('products.index') }}"><button class="btn-menu"><span>📦 محصولات</span></button></a>
            <a href="{{ route('about') }}"><button class="btn-menu"><span>ℹ️ درباره ما</span></button></a>
            <a href="{{ route('contact') }}"><button class="btn-menu"><span>📞 تماس با ما</span></button></a>
            
            <a href="{{ route('cart.index') }}">
                <button class="btn-menu">
                    <span>🛒 سبد
                    @php $cartCount = count(session('cart', [])); @endphp
                    @if($cartCount > 0) ({{ $cartCount }}) @endif
                    </span>
                </button>
            </a>
            
            @auth
                @if(Auth::user()->is_admin)
                    <a href="/admin"><button class="btn-menu"><span>🎛 ادمین</span></button></a>
                @endif
                <a href="{{ route('profile') }}"><button class="btn-menu"><span>👤 {{ Auth::user()->name }}</span></button></a>
                <form method="POST" action="/logout" style="display: contents;">
                    @csrf
                    <button type="submit" class="btn-menu"><span>🚪 خروج</span></button>
                </form>
            @else
                <a href="{{ route('login') }}"><button class="btn-menu"><span>👤 ورود / ثبت‌نام</span></button></a>
            @endauth
        </div>
    </div>

    <div class="search-panel" id="searchPanel">
        <div class="search-panel-header">
            <input type="search" 
                id="searchInput" 
                placeholder="جستجوی محصولات..." 
                autocomplete="off">
            <button class="search-close" id="searchClose" type="button">✕</button>
        </div>
        <div class="search-results" id="searchResults"></div>
    </div>
    <div class="search-overlay" id="searchOverlay"></div>

    </header>

<div class="mobile-overlay" id="mobileOverlay"></div>

