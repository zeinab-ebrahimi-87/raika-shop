<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'description',
        'price', 'image', 'is_active', 'is_featured', 'stock'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class)->withTrashed();
    }
    
    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class)->orderBy('sort_order');
    }


    public function reviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true)->latest();
    }

    // میانگین امتیاز
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }



    // قیمت پیش‌فرض (اگه variant داره، ارزون‌ترین رو بده)
    public function getDisplayPriceAttribute()
    {
        if ($this->variants->count() > 0) {
            return $this->variants->min('price');
        }
        return $this->price;
    }

    // موجودی کل
    public function getTotalStockAttribute()
    {
        if ($this->variants->count() > 0) {
            return $this->variants->sum('stock');
        }
        return $this->stock;
    }
}
