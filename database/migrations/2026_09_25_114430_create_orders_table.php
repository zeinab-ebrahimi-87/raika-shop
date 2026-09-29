<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');               // اسم خریدار
            $table->string('phone');              // تلفن
            $table->string('email')->nullable();  // ایمیل
            $table->text('address');              // آدرس
            $table->string('city');               // شهر
            $table->string('postal_code');        // کد پستی
            $table->integer('total');             // جمع کل
            $table->string('status')->default('pending'); // وضعیت
            $table->text('notes')->nullable();    // توضیحات
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('product_name');       // اسم محصول (اسنپ‌شات)
            $table->integer('price');             // قیمت (اسنپ‌شات)
            $table->integer('qty');               // تعداد
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
