<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'شکلات فانتزی',   'slug' => 'reception-chocolate'],
            ['name' => 'شکلات هدیه',     'slug' => 'gift-chocolate'],
            ['name' => 'شکلات وانیلی',   'slug' => 'vanilla-chocolate'],
            ['name' => 'شکلات قلبی',     'slug' => 'heart-chocolate'],
            ['name' => 'شکلات فندقی',    'slug' => 'hazelnut-chocolate'],
            ['name' => 'شکلات شیری',     'slug' => 'milk-chocolate'],
            ['name' => 'شکلات سیگاری',   'slug' => 'cigarette-chocolate'],
            ['name' => 'شکلات تلخ',      'slug' => 'dark-chocolate'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }
    }
}