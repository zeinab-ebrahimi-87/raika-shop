@extends('layouts.app')

@section('title', $product->name . ' | رایکا')

@section('content')

<nav class="breadcrumb">
    <a href="{{ route('home') }}" class="breadcrumb-item">🏠 خانه</a>
    <span class="breadcrumb-separator">›</span>
    <a href="{{ route('products.index') }}" class="breadcrumb-item">📦 محصولات</a>
    <span class="breadcrumb-separator">›</span>
    <a href="{{ route('products.category', $product->category->slug) }}" class="breadcrumb-item">
        {{ $product->category->name }}
    </a>
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">{{ $product->name }}</span>
</nav>


<div style="max-width: 1000px; margin: 0 auto;">
    
    {{-- لینک برگشت --}}
    <a href="{{ route('products.index') }}" style="color: #825B32; text-decoration: none; display: inline-block; margin-bottom: 20px;">
        ← بازگشت به محصولات
    </a>

    <div class="product-detail">
        {{-- عکس --}}
        <div class="product-gallery">
            @php
                // جمع کردن همه عکس‌ها: عکس اصلی + گالری
                $allImages = [];
                if ($product->image && file_exists(public_path('images/products/' . $product->image))) {
                    $allImages[] = $product->image;
                }
                foreach ($product->images as $img) {
                    if (file_exists(public_path('images/products/' . $img->image))) {
                        $allImages[] = $img->image;
                    }
                }
                if (empty($allImages)) {
                    $allImages[] = 'placeholder.jpg';
                }
            @endphp
            
            <!-- عکس اصلی -->
            <div class="main-image-wrapper">
                <img id="mainImage" src="{{ asset('images/products/' . $allImages[0]) }}" alt="{{ $product->name }}" class="main-image">
                
                @if(count($allImages) > 1)
                    <!-- دکمه‌های چپ/راست -->
                    <button class="gallery-nav gallery-prev" onclick="changeImage(-1)">‹</button>
                    <button class="gallery-nav gallery-next" onclick="changeImage(1)">›</button>
                    
                    <!-- شمارنده -->
                    <div class="image-counter">
                        <span id="currentIndex">1</span> / {{ count($allImages) }}
                    </div>
                @endif
        </div>
    
        <!-- تصاویر کوچک (Thumbnails) -->
        @if(count($allImages) > 1)
            <div class="thumbnails">
                @foreach($allImages as $index => $img)
                    <div class="thumb {{ $index === 0 ? 'active' : '' }}" onclick="setImage({{ $index }})">
                        <img src="{{ asset('images/products/' . $img) }}" alt="">
                    </div>
                @endforeach
            </div>
        @endif
    
    
    </div>

        {{-- اطلاعات --}}
        <div class="product-detail-info">
            <h1>{{ $product->name }}</h1>
            
            <p class="product-category">
                دسته: <a href="{{ route('products.category', $product->category->slug) }}">
                    {{ $product->category->name }}
                </a>
            </p>

            {{-- ⚠️ Variants --}}
            @if($product->variants->count() > 0)
                <div class="variants-section">
                    <label class="variants-label">انتخاب وزن/تعداد:</label>
                    <div class="variants-list">
                        @foreach($product->variants as $index => $variant)
                            <label class="variant-option {{ $variant->stock <= 0 ? 'out-of-stock' : '' }}">
                                <input type="radio" 
                                    name="variant_id" 
                                    value="{{ $variant->id }}"
                                    data-price="{{ $variant->price }}"
                                    data-price-formatted="{{ number_format($variant->price) }}"
                                    data-stock="{{ $variant->stock }}"
                                    {{ $variant->is_default && $variant->stock > 0 ? 'checked' : '' }}
                                    {{ $variant->stock <= 0 ? 'disabled' : '' }}>
                                <span class="variant-content">
                                    <span class="variant-label">{{ $variant->label }}</span>
                                    <span class="variant-price">{{ number_format($variant->price) }} تومان</span>
                                    @if($variant->stock <= 0)
                                        <span class="variant-stock-badge">ناموجود</span>
                                    @endif
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- قیمت نمایشی --}}
            <p class="product-detail-price" id="displayPrice">
                @if($product->variants->count() > 0)
                    {{ number_format($product->display_price) }} تومان
                @else
                    {{ number_format($product->price) }} تومان
                @endif
            </p>

            <div class="product-detail-description">
                <h3>توضیحات:</h3>
                <p>{{ $product->description ?? 'توضیحی برای این محصول ثبت نشده.' }}</p>
            </div>

            <div class="product-stock" id="stockInfo">
                @if($product->variants->count() > 0)
                    @php $firstVariant = $product->variants->where('is_default', true)->first() ?? $product->variants->first(); @endphp
                    @if($firstVariant && $firstVariant->stock > 0)
                        <span style="color: green;">✅ موجود در انبار ({{ $firstVariant->stock }} عدد)</span>
                    @else
                        <span style="color: red;">❌ ناموجود</span>
                    @endif
                @else
                    @if($product->stock > 0)
                        <span style="color: green;">✅ موجود در انبار ({{ $product->stock }} عدد)</span>
                    @else
                        <span style="color: red;">❌ ناموجود</span>
                    @endif
                @endif
            </div>

            {{-- دکمه افزودن به سبد --}}
            <form method="POST" action="{{ route('cart.add', $product->id) }}" id="addToCartForm">
                @csrf
                <input type="hidden" name="variant_id" id="selectedVariantId" value="">
                <button type="submit" class="add-to-cart-btn" id="addToCartBtn">
                    🛒 افزودن به سبد خرید
                </button>
            </form>

            <!-- ======  نظرات و امتیاز ====== -->
            <div class="reviews-section">
                
                <div class="reviews-header">
                    <h2>💬 نظرات و امتیاز</h2>
                    
                    @if($product->reviews_count > 0)
                        <div class="reviews-summary">
                            <div class="average-rating">
                                <span class="rating-number">{{ number_format($product->average_rating, 1) }}</span>
                                <span class="rating-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= round($product->average_rating))
                                            ⭐
                                        @else
                                            ☆
                                        @endif
                                    @endfor
                                </span>
                                <span class="rating-count">({{ $product->reviews_count }} نظر)</span>
                            </div>
                        </div>
                    @endif
                </div>
                
                {{-- فرم ثبت نظر --}}
                @auth
                    @php
                        $userReview = \App\Models\Review::where('product_id', $product->id)
                            ->where('user_id', Auth::id())
                            ->first();
                    @endphp
                    
                    @if(!$userReview)
                        <div class="review-form-wrapper">
                            <h3>✍️ نظرت رو بنویس</h3>
                            
                            <form method="POST" action="{{ route('reviews.store', $product->id) }}" class="review-form">
                                @csrf
                                
                                {{-- امتیاز --}}
                                <div class="form-group">
                                    <label>امتیازت چنده؟</label>
                                    <div class="star-rating-input">
                                        @for($i = 5; $i >= 1; $i--)
                                            <input type="radio" 
                                                id="star{{ $i }}" 
                                                name="rating" 
                                                value="{{ $i }}" 
                                                {{ $i == 5 ? 'checked' : '' }}>
                                            <label for="star{{ $i }}" title="{{ $i }} ستاره">⭐</label>
                                        @endfor
                                    </div>
                                </div>
                                
                                {{-- متن --}}
                                <div class="form-group">
                                    <label for="comment">نظرت:</label>
                                    <textarea id="comment" 
                                            name="comment" 
                                            rows="4" 
                                            placeholder="تجربه‌ات رو با ما به اشتراک بذار..." 
                                            required
                                            minlength="5"
                                            maxlength="1000"></textarea>
                                </div>
                                
                                <button type="submit" class="btn-submit-review">
                                    📩 ثبت نظر
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="user-review-notice">
                            @if($userReview->is_approved)
                                ✅ نظرت ثبت شده و تأیید شده. ممنون! 🌟
                            @else
                                ⏳ نظرت در انتظار تأیید ادمینه.
                            @endif
                        </div>
                    @endif
                @else
                    <div class="login-to-review">
                        <a href="{{ route('login') }}">وارد شو</a> تا بتونی نظر بدی.
                    </div>
                @endauth
                
                {{-- لیست نظرات --}}
                <div class="reviews-list">
                    @forelse($product->reviews as $review)
                        <div class="review-item">
                            <div class="review-header">
                                <div class="reviewer-info">
                                    <div class="reviewer-avatar">
                                        {{ mb_substr($review->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="reviewer-name">{{ $review->user->name }}</div>
                                        <div class="review-date">{{ $review->created_at->format('Y/m/d') }}</div>
                                    </div>
                                </div>
                                <div class="review-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            ⭐
                                        @else
                                            ☆
                                        @endif
                                    @endfor
                                </div>
                            </div>
                            <p class="review-comment">{{ $review->comment }}</p>
                            
                            @auth
                                @if(Auth::user()->is_admin)
                                    <div class="review-admin-actions">
                                        <form method="POST" action="{{ route('admin.reviews.destroy', $review->id) }}" onsubmit="return confirm('حذف بشه؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete-review">🗑 حذف</button>
                                        </form>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    @empty
                        <div class="no-reviews">
                            <div class="no-reviews-icon">💭</div>
                            <p>هنوز نظری ثبت نشده. اولین نفر باش!</p>
                        </div>
                    @endforelse
                </div>
                
            </div>

        </div>


    </div>

</div>

<style>
    .product-detail {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        background: #FFEAC5;
        border-radius: 25px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    
    .product-detail-image {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        height: 400px;
    }
    
    .product-detail-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .product-detail-info h1 {
        color: #603F26;
        font-size: 2em;
        margin-bottom: 15px;
    }
    
    .product-category {
        color: #825B32;
        margin-bottom: 20px;
    }
    
    .product-category a {
        color: #603F26;
        font-weight: bold;
        text-decoration: none;
    }
    
    .product-category a:hover {
        text-decoration: underline;
    }
    
    .product-detail-price {
        color: #603F26;
        font-size: 1.8em;
        font-weight: bold;
        margin: 20px 0;
        padding: 15px 0;
        border-top: 2px dashed #825B32;
        border-bottom: 2px dashed #825B32;
    }
    
    .product-detail-description h3 {
        color: #603F26;
        margin-bottom: 10px;
    }
    
    .product-detail-description p {
        color: #2C1C10;
        line-height: 1.8;
    }
    
    .product-stock {
        margin: 20px 0;
        font-weight: bold;
    }
    
    .add-to-cart-btn {
        width: 100%;
        background: #603F26;
        color: #FFEAC5;
        border: none;
        padding: 18px;
        border-radius: 15px;
        font-size: 1.2em;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
        font-family: IRANSans;
    }
    
    .add-to-cart-btn:hover:not(:disabled) {
        background: #825B32;
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }
    
    .add-to-cart-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    @media (max-width: 768px) {
        .product-detail {
            grid-template-columns: 1fr;
        }
        .product-detail-image {
            height: 300px;
        }
    }

    /* ============================================
   گالری محصول (اسلایدر)
   ============================================ */

.product-gallery {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.main-image-wrapper {
    position: relative;
    background: white;
    border-radius: 20px;
    overflow: hidden;
    height: 400px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.main-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: opacity 0.3s, transform 0.3s;
}

/* دکمه‌های چپ/راست */
.gallery-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: rgba(96, 63, 38, 0.9);
    color: #FFEAC5;
    border: 2px solid #FFEAC5;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    line-height: 1;
    padding: 0;
    opacity: 0.7;
}

.gallery-nav:hover {
    background: #603F26;
    opacity: 1;
    transform: translateY(-50%) scale(1.15);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.4);
}

.gallery-prev {
    right: 15px;
}

.gallery-next {
    left: 15px;
}

/* شمارنده */
.image-counter {
    position: absolute;
    bottom: 15px;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(96, 63, 38, 0.9);
    color: #FFEAC5;
    padding: 6px 16px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.9em;
    backdrop-filter: blur(10px);
}

/* Thumbnails */
.thumbnails {
    display: flex;
    gap: 10px;
    overflow-x: auto;
    padding: 5px;
    scrollbar-width: thin;
}

.thumbnails::-webkit-scrollbar {
    height: 6px;
}

.thumbnails::-webkit-scrollbar-thumb {
    background: #825B32;
    border-radius: 10px;
}

.thumb {
    flex-shrink: 0;
    width: 80px;
    height: 80px;
    border-radius: 12px;
    overflow: hidden;
    cursor: pointer;
    border: 3px solid transparent;
    transition: all 0.3s;
    background: white;
}

.thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.thumb:hover {
    transform: translateY(-3px);
    border-color: #825B32;
    box-shadow: 0 5px 15px rgba(96, 63, 38, 0.3);
}

.thumb.active {
    border-color: #603F26;
    box-shadow: 0 5px 15px rgba(96, 63, 38, 0.5);
    transform: scale(1.05);
}

/* موبایل */
@media (max-width: 768px) {
    .main-image-wrapper {
        height: 300px;
    }
    
    .gallery-nav {
        width: 38px;
        height: 38px;
        font-size: 22px;
    }
    
    .gallery-prev { right: 10px; }
    .gallery-next { left: 10px; }
    
    .thumb {
        width: 65px;
        height: 65px;
    }
    
    .image-counter {
        font-size: 0.8em;
        padding: 5px 12px;
    }
}
</style>
<script>
    var images = [
        @foreach($allImages as $img)
            "{{ asset('images/products/' . $img) }}",
        @endforeach
    ];
    var currentIndex = 0;
    
    function setImage(index) {
        if (index < 0) index = images.length - 1;
        if (index >= images.length) index = 0;
        
        currentIndex = index;
        document.getElementById('mainImage').src = images[currentIndex];
        document.getElementById('currentIndex').textContent = currentIndex + 1;
        
        document.querySelectorAll('.thumb').forEach(function(t, i) {
            t.classList.toggle('active', i === currentIndex);
        });
    }
    
    function changeImage(direction) {
        setImage(currentIndex + direction);
    }
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var variantRadios = document.querySelectorAll('input[name="variant_id"]');
    var displayPrice = document.getElementById('displayPrice');
    var stockInfo = document.getElementById('stockInfo');
    var selectedVariantId = document.getElementById('selectedVariantId');
    var addToCartBtn = document.getElementById('addToCartBtn');
    
    if (variantRadios.length === 0) return;
    
    // مقدار اولیه
    var checkedRadio = document.querySelector('input[name="variant_id"]:checked');
    if (checkedRadio) {
        selectedVariantId.value = checkedRadio.value;
    }
    
    variantRadios.forEach(function(radio) {
        radio.addEventListener('change', function() {
            var price = this.dataset.priceFormatted;
            var stock = parseInt(this.dataset.stock);
            
            // آپدیت قیمت
            displayPrice.textContent = price + ' تومان';
            
            // آپدیت موجودی
            if (stock > 0) {
                stockInfo.innerHTML = '<span style="color: green;">✅ موجود در انبار (' + stock + ' عدد)</span>';
                addToCartBtn.disabled = false;
                addToCartBtn.textContent = '🛒 افزودن به سبد خرید';
            } else {
                stockInfo.innerHTML = '<span style="color: red;">❌ ناموجود</span>';
                addToCartBtn.disabled = true;
                addToCartBtn.textContent = '❌ ناموجود';
            }
            
            // آپدیت variant_id
            selectedVariantId.value = this.value;
        });
    });
});
</script>
@endsection