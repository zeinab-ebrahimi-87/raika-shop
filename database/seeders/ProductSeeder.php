<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // دسته ۱: شکلات فانتزی
            ['category_id' => 1, 'name' => 'شکلات فانتزی آجیلی', 'slug' => 'fantasy-nutty', 'price' => 180000, 'description' => 'شکلات فانتزی با مغز آجیل', 'stock' => 50],
            ['category_id' => 1, 'name' => 'شکلات فانتزی مغزدار', 'slug' => 'fantasy-filled', 'price' => 100000, 'description' => 'شکلات فانتزی مغزدار', 'stock' => 80],
            ['category_id' => 1, 'name' => 'شکلات فانتزی قرمز', 'slug' => 'fantasy-red', 'price' => 50000, 'description' => 'شکلات فانتزی قرمز رنگ', 'stock' => 120],

            // دسته ۲: شکلات هدیه
            ['category_id' => 2, 'name' => 'شکلات هدیه پک h1', 'slug' => 'gift-h1', 'price' => 250000, 'description' => 'پک هدیه شکلات شماره ۱', 'stock' => 30],
            ['category_id' => 2, 'name' => 'شکلات هدیه پک h2', 'slug' => 'gift-h2', 'price' => 150000, 'description' => 'پک هدیه شکلات شماره ۲', 'stock' => 40],

            // دسته ۳: شکلات وانیلی
            ['category_id' => 3, 'name' => 'شکلات وانیلی جعبه ۱۵ تایی', 'slug' => 'vanilla-15', 'price' => 100000, 'description' => 'شکلات وانیلی در جعبه ۱۵ تایی', 'stock' => 60],
            ['category_id' => 3, 'name' => 'شکلات وانیلی', 'slug' => 'vanilla-250g', 'price' => 120000, 'description' => 'شکلات وانیلی ۲۵۰ گرمی', 'stock' => 100],
            ['category_id' => 3, 'name' => 'شکلات وانیلی مغزدار', 'slug' => 'vanilla-filled', 'price' => 90000, 'description' => 'شکلات وانیلی مغزدار', 'stock' => 70],

            // دسته ۴: شکلات قلبی
            ['category_id' => 4, 'name' => 'شکلات قلبی سفید و صورتی', 'slug' => 'heart-pink', 'price' => 60000, 'description' => 'شکلات قلبی رنگ صورتی', 'stock' => 90],
            ['category_id' => 4, 'name' => 'شکلات قلبی جعبه‌ای', 'slug' => 'heart-box', 'price' => 200000, 'description' => 'شکلات قلبی در جعبه', 'stock' => 25],

            // دسته ۵: شکلات فندقی
            ['category_id' => 5, 'name' => 'شکلات فندقی ۳۴٪', 'slug' => 'hazelnut-34', 'price' => 90000, 'description' => 'شکلات فندقی با ۳۴ درصد فندق', 'stock' => 60],
            ['category_id' => 5, 'name' => 'شکلات صبحانه فندقی', 'slug' => 'hazelnut-breakfast', 'price' => 110000, 'description' => 'شکلات صبحانه فندقی', 'stock' => 80],

            // دسته ۶: شکلات شیری
            ['category_id' => 6, 'name' => 'شکلات شیری کد milk001', 'slug' => 'milk-001', 'price' => 50000, 'description' => 'شکلات شیری کلاسیک', 'stock' => 150],
            ['category_id' => 6, 'name' => 'شکلات شیری کد milk002', 'slug' => 'milk-002', 'price' => 60000, 'description' => 'شکلات شیری کد ۲', 'stock' => 100],

            // دسته ۷: شکلات سیگاری
            ['category_id' => 7, 'name' => 'شکلات سیگاری وانیلی', 'slug' => 'cig-vanilla', 'price' => 60000, 'description' => 'شکلات سیگاری وانیلی', 'stock' => 80],
            ['category_id' => 7, 'name' => 'شکلات سیگاری دارچینی', 'slug' => 'cig-cinnamon', 'price' => 55000, 'description' => 'شکلات سیگاری دارچینی', 'stock' => 70],

            // دسته ۸: شکلات تلخ
            ['category_id' => 8, 'name' => 'شکلات تلخ ۱۰۰٪', 'slug' => 'dark-100', 'price' => 300000, 'description' => 'شکلات تلخ ۱۰۰ درصد', 'stock' => 20],
            ['category_id' => 8, 'name' => 'شکلات تلخ ۷۵٪', 'slug' => 'dark-75', 'price' => 240000, 'description' => 'شکلات تلخ ۷۵ درصد', 'stock' => 35],
        ];

        foreach ($products as $p) {
            Product::create($p);
        }
    }
}