<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');                  // اسم محصول
            $table->string('slug')->unique();        // آدرس یکتا
            $table->text('description')->nullable(); // توضیحات
            $table->integer('price');                // قیمت (تومان)
            $table->string('image')->nullable();     // عکس
            $table->boolean('is_active')->default(true);  // فعال؟
            $table->boolean('is_featured')->default(false); // ویژه؟
            $table->integer('stock')->default(0);    // موجودی
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};