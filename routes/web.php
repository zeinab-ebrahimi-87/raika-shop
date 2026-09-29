<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

// ============================================
// صفحات عمومی
// ============================================

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// ============================================
// محصولات
// ============================================

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/category/{slug}', [ProductController::class, 'category'])->name('products.category');

// ============================================
// جستجوی کاربر
// ============================================

Route::get('/search', [ProductController::class, 'search'])->name('search');

// ============================================
// Auth
// ============================================

Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->middleware('guest');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/profile', [AuthController::class, 'profile'])->name('profile')->middleware('auth');

// نظرات
Route::post('/products/{id}/reviews', [ReviewController::class, 'store'])->name('reviews.store')->middleware('auth');


// ============================================
// سبد خرید
// ============================================

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/update/{key}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{key}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// ============================================
// سفارشات
// ============================================

Route::get('/checkout', [OrderController::class, 'checkout'])->name('order.checkout');
Route::post('/checkout', [OrderController::class, 'store'])->name('order.store');
Route::get('/order/{id}/success', [OrderController::class, 'success'])->name('order.success');

// ============================================
// پنل ادمین
// ============================================

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    
    // داشبورد
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // ============================================
    // محصولات
    // ============================================
    
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [AdminProductController::class, 'destroy'])->name('products.destroy');
    
    // ⚠️ حذف عکس — باید قبل از products.destroy باشه یا با مسیر متفاوت
    Route::delete('/product-images/{id}', [AdminProductController::class, 'destroyImage'])->name('product-images.destroy');
    

    // مدیریت دسته‌بندی
    Route::get('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{id}', [\App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::put('/categories/{id}/restore', [\App\Http\Controllers\Admin\CategoryController::class, 'restore'])->name('categories.restore');
    Route::delete('/categories/{id}/force', [\App\Http\Controllers\Admin\CategoryController::class, 'forceDelete'])->name('categories.forceDelete');
    Route::put('/categories/{id}/toggle', [\App\Http\Controllers\Admin\CategoryController::class, 'toggleActive'])->name('categories.toggleActive');

    // ============================================
    // سفارشات
    // ============================================
    
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    

    // ============================================
    // مدیریت نظرات
    // ============================================
    Route::get('/reviews', [\App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('reviews.index');
    Route::put('/reviews/{id}/approve', [\App\Http\Controllers\Admin\ReviewController::class, 'approve'])->name('reviews.approve');
    Route::delete('/reviews/{id}/reject', [\App\Http\Controllers\Admin\ReviewController::class, 'reject'])->name('reviews.reject');
    Route::delete('/reviews/{id}', [\App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');


    // ============================================
    // جستجوی ادمین
    // ============================================
    
    Route::get('/search/products', [AdminProductController::class, 'search'])->name('products.search');
    Route::get('/search/orders', [AdminOrderController::class, 'search'])->name('orders.search');
});


// ============================================
// Sitemap
// ============================================

Route::get('/sitemap.xml', function () {
    $products = \App\Models\Product::all();
    $categories = \App\Models\Category::all();
    
    return response()->view('sitemap', compact('products', 'categories'))
        ->header('Content-Type', 'text/xml');
});

