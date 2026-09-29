@extends('layouts.app')

@section('title', 'مدیریت نظرات | رایکا')

@section('content')

<a href="{{ route('admin.dashboard') }}" class="admin-back-btn">
    <span class="arrow">←</span>
    بازگشت به داشبورد
</a>

<div class="admin-reviews-page">
    
    <div class="page-header">
        <h1>💬 مدیریت نظرات</h1>
        <p class="subtitle">تأیید، رد یا حذف نظرات کاربران</p>
    </div>
    
    
    <!-- ============================================
         نظرات در انتظار تأیید
         ============================================ -->
    <div class="reviews-section">
        <div class="section-header">
            <h2>⏳ در انتظار تأیید</h2>
            <span class="count-badge">{{ $pendingReviews->count() }}</span>
        </div>
        
        @if($pendingReviews->count() > 0)
            <div class="reviews-grid">
                @foreach($pendingReviews as $review)
                    <div class="review-card pending">
                        <div class="review-product">
                            <span class="product-label">محصول:</span>
                            <a href="{{ route('products.show', $review->product->slug) }}" target="_blank" class="product-link">
                                {{ $review->product->name }}
                            </a>
                        </div>
                        
                        <div class="review-user">
                            <div class="user-avatar">{{ mb_substr($review->user->name, 0, 1) }}</div>
                            <div>
                                <div class="user-name">{{ $review->user->name }}</div>
                                <div class="review-date">{{ $review->created_at->format('Y/m/d H:i') }}</div>
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
                            <span class="rating-value">({{ $review->rating }}/5)</span>
                        </div>
                        
                        <p class="review-comment">{{ $review->comment }}</p>
                        
                        <div class="review-actions">
                            <form method="POST" action="{{ route('admin.reviews.approve', $review->id) }}" style="display: inline;">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn-approve">✅ تأیید</button>
                            </form>
                            
                            <form method="POST" action="{{ route('admin.reviews.reject', $review->id) }}" onsubmit="return confirm('مطمئنی میخوای رد کنی؟')" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-reject">❌ رد</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state-mini">
                <div class="empty-icon">🎉</div>
                <p>هیچ نظر جدیدی نیست. عالیه!</p>
            </div>
        @endif
    </div>
    
    <!-- ============================================
         نظرات تأیید شده
         ============================================ -->
    <div class="reviews-section">
        <div class="section-header">
            <h2>✅ نظرات تأیید شده</h2>
            <span class="count-badge approved">{{ $approvedReviews->total() }}</span>
        </div>
        
        @if($approvedReviews->count() > 0)
            <div class="approved-table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>محصول</th>
                            <th>کاربر</th>
                            <th>امتیاز</th>
                            <th>نظر</th>
                            <th>تاریخ</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($approvedReviews as $review)
                            <tr>
                                <td>
                                    <a href="{{ route('products.show', $review->product->slug) }}" target="_blank" class="product-link">
                                        {{ $review->product->name }}
                                    </a>
                                </td>
                                <td>{{ $review->user->name }}</td>
                                <td>
                                    <span class="rating-stars-mini">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                ⭐
                                            @else
                                                ☆
                                            @endif
                                        @endfor
                                    </span>
                                </td>
                                <td class="comment-cell">{{ Str::limit($review->comment, 60) }}</td>
                                <td>{{ $review->created_at->format('Y/m/d') }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.reviews.destroy', $review->id) }}" onsubmit="return confirm('حذف بشه؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete">🗑</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="pagination-wrapper">
                {{ $approvedReviews->links() }}
            </div>
        @else
            <div class="empty-state-mini">
                <div class="empty-icon">📭</div>
                <p>هنوز نظر تأیید شده‌ای نداری.</p>
            </div>
        @endif
    </div>
    
</div>

<style>
.admin-reviews-page {
    max-width: 1200px;
    margin: 0 auto;
    animation: fadeInUp 0.6s ease-out;
}

.page-header {
    margin-bottom: 30px;
}

.page-header h1 {
    color: #603F26;
    font-size: 2em;
    margin-bottom: 5px;
}

.subtitle {
    color: #825B32;
    font-size: 0.95em;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    padding: 15px 20px;
    border-radius: 12px;
    margin-bottom: 25px;
    border-right: 4px solid #28a745;
}

/* بخش‌ها */
.reviews-section {
    margin-bottom: 50px;
}

.section-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 2px dashed #FFEAC5;
}

.section-header h2 {
    color: #603F26;
    font-size: 1.4em;
}

.count-badge {
    background: linear-gradient(135deg, #ffc107, #ff9800);
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.85em;
    font-weight: 700;
    box-shadow: 0 3px 10px rgba(255, 152, 0, 0.3);
}

.count-badge.approved {
    background: linear-gradient(135deg, #28a745, #20c997);
    box-shadow: 0 3px 10px rgba(40, 167, 69, 0.3);
}

/* گرید نظرات در انتظار */
.reviews-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
    gap: 20px;
}

.review-card {
    background: white;
    border-radius: 18px;
    padding: 22px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    border: 2px solid transparent;
    transition: all 0.3s;
}

.review-card.pending {
    border-color: #ffc107;
    background: linear-gradient(180deg, #fffbf2, white);
}

.review-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
}

.review-product {
    background: #FFEAC5;
    padding: 10px 14px;
    border-radius: 10px;
    margin-bottom: 15px;
    font-size: 0.9em;
}

.product-label {
    color: #825B32;
    font-weight: 600;
}

.product-link {
    color: #603F26;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.3s;
}

.product-link:hover {
    color: #825B32;
    text-decoration: underline;
}

.review-user {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 15px;
}

.user-avatar {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: linear-gradient(135deg, #603F26, #825B32);
    color: #FFEAC5;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.1em;
    flex-shrink: 0;
}

.user-name {
    font-weight: 700;
    color: #603F26;
    font-size: 0.95em;
}

.review-date {
    color: #825B32;
    font-size: 0.8em;
    opacity: 0.8;
}

.review-rating {
    margin-bottom: 12px;
    font-size: 1em;
    letter-spacing: -1px;
}

.rating-value {
    color: #825B32;
    font-size: 0.8em;
    font-weight: 600;
    margin-right: 5px;
    letter-spacing: 0;
}

.review-comment {
    color: #2C1C10;
    line-height: 1.7;
    font-size: 0.9em;
    margin-bottom: 18px;
    padding: 12px;
    background: #fffbf2;
    border-radius: 10px;
    border-right: 3px solid #825B32;
}

.review-actions {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

.btn-approve,
.btn-reject {
    padding: 10px 20px;
    border: none;
    border-radius: 10px;
    font-family: 'IRANSans', Tahoma;
    font-weight: 600;
    font-size: 0.9em;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-approve {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    box-shadow: 0 3px 10px rgba(40, 167, 69, 0.2);
}

.btn-approve:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(40, 167, 69, 0.4);
}

.btn-reject {
    background: #f8d7da;
    color: #721c24;
}

.btn-reject:hover {
    background: #d32f2f;
    color: white;
    transform: translateY(-2px);
}

/* جدول تأیید شده */
.approved-table-wrapper {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    overflow-x: auto;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 700px;
}

.admin-table thead {
    background: linear-gradient(135deg, #603F26, #825B32);
}

.admin-table th {
    padding: 15px;
    color: #FFEAC5;
    font-weight: 600;
    text-align: right;
    font-size: 0.9em;
    white-space: nowrap;
}

.admin-table tbody tr {
    border-bottom: 1px solid #f0e0c8;
    transition: background 0.3s;
}

.admin-table tbody tr:hover {
    background: #fffbf2;
}

.admin-table td {
    padding: 15px;
    color: #2C1C10;
    font-size: 0.9em;
    vertical-align: middle;
}

.rating-stars-mini {
    font-size: 0.9em;
    letter-spacing: -1px;
}

.comment-cell {
    max-width: 250px;
    opacity: 0.85;
}

.btn-delete {
    background: #f8d7da;
    color: #721c24;
    border: none;
    width: 36px;
    height: 36px;
    border-radius: 10px;
    cursor: pointer;
    font-size: 15px;
    transition: all 0.3s;
}

.btn-delete:hover {
    background: #d32f2f;
    color: white;
    transform: scale(1.1);
}

/* Empty State */
.empty-state-mini {
    text-align: center;
    padding: 40px 20px;
    background: white;
    border-radius: 15px;
    color: #825B32;
}

.empty-icon {
    font-size: 3em;
    margin-bottom: 10px;
    opacity: 0.5;
}

.pagination-wrapper {
    margin-top: 20px;
    display: flex;
    justify-content: center;
}

/* موبایل */
@media (max-width: 768px) {
    .page-header h1 {
        font-size: 1.5em;
    }
    
    .section-header h2 {
        font-size: 1.1em;
    }
    
    .reviews-grid {
        grid-template-columns: 1fr;
    }
    
    .review-card {
        padding: 18px;
    }
    
    .review-actions {
        flex-direction: column;
    }
    
    .btn-approve,
    .btn-reject {
        width: 100%;
    }
    
    .admin-table {
        min-width: auto;
    }
    
    .admin-table thead {
        display: none;
    }
    
    .admin-table tbody tr {
        display: block;
        padding: 15px;
        margin-bottom: 10px;
        border-radius: 12px;
        background: white;
        border: 1px solid #f0e0c8;
    }
    
    .admin-table td {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px dashed #f0e0c8;
    }
    
    .admin-table td:last-child {
        border-bottom: none;
    }
    
    .admin-table td::before {
        content: attr(data-label);
        font-weight: 700;
        color: #825B32;
        font-size: 0.85em;
    }
}
</style>

@endsection