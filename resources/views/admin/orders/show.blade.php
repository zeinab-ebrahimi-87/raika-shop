@extends('layouts.app')

@section('title', 'سفارش #' . $order->id)

@section('content')

<div>
    <a href="{{ route('admin.dashboard') }}" class="admin-back-btn">
    <span class="arrow">←</span>
    بازگشت به داشبورد
    </a>
</div>

<div>
    <a href="{{ route('admin.orders.index') }}" class="admin-back-btn">
        <span class="arrow">←</span>
        بازگشت به سفارشات
    </a>
</div>

<div style="max-width: 800px; margin: 0 auto;">
    <h1 style="color: #603F26; margin-bottom: 30px;">سفارش #{{ $order->id }}</h1>

    <div style="background: white; padding: 25px; border-radius: 15px; margin-bottom: 20px;">
        <h2 style="color: #603F26; margin-bottom: 15px;">اطلاعات مشتری</h2>
        <p><strong>نام:</strong> {{ $order->name }}</p>
        <p><strong>تلفن:</strong> {{ $order->phone }}</p>
        <p><strong>ایمیل:</strong> {{ $order->email ?? '-' }}</p>
        <p><strong>شهر:</strong> {{ $order->city }}</p>
        <p><strong>آدرس:</strong> {{ $order->address }}</p>
        <p><strong>کد پستی:</strong> {{ $order->postal_code }}</p>
        @if($order->notes)<p><strong>توضیحات:</strong> {{ $order->notes }}</p>@endif
    </div>

    <div style="background: white; padding: 25px; border-radius: 15px; margin-bottom: 20px;">
        <h2 style="color: #603F26; margin-bottom: 15px;">محصولات</h2>
        @foreach($order->items as $item)
            <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee;">
                <span>{{ $item->product_name }} × {{ $item->qty }}</span>
                <span>{{ number_format($item->price * $item->qty) }} ت</span>
            </div>
        @endforeach
        <div style="margin-top: 15px; padding-top: 15px; border-top: 2px solid #603F26; font-size: 1.2em;">
            <strong>جمع کل: {{ number_format($order->total) }} تومان</strong>
        </div>
    </div>

    <div style="background: white; padding: 25px; border-radius: 15px;">
        <h2 style="color: #603F26; margin-bottom: 15px;">تغییر وضعیت</h2>
        <form method="POST" action="{{ route('admin.orders.status', $order->id) }}">
            @csrf
            @method('PUT')
            <select name="status" style="padding: 12px; border: 2px solid #825B32; border-radius: 10px; font-family: Tahoma; width: 100%; margin-bottom: 15px;">
                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>در انتظار</option>
                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>در حال پردازش</option>
                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>ارسال شده</option>
                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>تحویل شده</option>
                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>لغو شده</option>
            </select>
            <button type="submit" style="width: 100%; background: #28a745; color: white; border: none; padding: 12px; border-radius: 10px; font-weight: bold; cursor: pointer; font-family: Tahoma;">
                💾 ذخیره
            </button>
        </form>
    </div>
</div>
@endsection