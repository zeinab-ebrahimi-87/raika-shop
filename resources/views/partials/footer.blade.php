<footer>
    <div class="footer">
        <div class="con">
            <h2>محصولات</h2>
            <a href="{{ route('products.index') }}">همه محصولات</a>
            <a href="{{ route('products.category', 'vanilla-chocolate') }}">شکلات وانیلی</a>
            <a href="{{ route('products.category', 'dark-chocolate') }}">شکلات تلخ</a>
        </div>
        <div class="con">
            <h2>درباره ما</h2>
            <a href="{{ route('about') }}">درباره ما</a>
            <a href="{{ route('contact') }}">تماس با ما</a>
        </div>
        <div class="con">
            <h2>حساب کاربری</h2>
            <a href="{{ route('login') }}">ورود</a>
            <a href="{{ route('register') }}">ثبت‌نام</a>
            <a href="{{ route('cart.index') }}">سبد خرید</a>
        </div>
    </div>
    <p>&copy; ۱۴۰۴ شکلات فروشی رایکا. تمامی حقوق محفوظ است.</p>
    <p>طراحی و توسعه توسط زینب ابراهیمی</p>
</footer>