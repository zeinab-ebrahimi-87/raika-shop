@extends('layouts.app')

@section('title', 'سفارش موفق | رایکا')

@section('content')

<div style="max-width: 700px; margin: 40px auto; text-align: center;">
    <div style="background: rgba(255, 255, 255, 0.9); padding: 60px 30px; border-radius: 25px;">
        <div style="font-size: 5em; margin-bottom: 20px;">✅</div>
        <h1 style="color: #28a745; margin-bottom: 20px;">سفارش شما ثبت شد!</h1>
        <p style="color: #825B32; font-size: 1.2em; margin-bottom: 30px;">
            شماره سفارش: <strong>#{{ $order->id }}</strong>
        </p>

        <div style="background: #FFEAC5; padding: 20px; border-radius: 15px; text-align: right; margin-bottom: 30px;">
            <p style="margin-bottom: 10px;"><strong>نام:</strong> {{ $order->name }}</p>
            <p style="margin-bottom: 10px;"><strong>تلفن:</strong> {{ $order->phone }}</p>
            <p style="margin-bottom: 10px;"><strong>آدرس:</strong> {{ $order->city }} - {{ $order->address }}</p>
            <p style="margin-bottom: 10px;"><strong>جمع کل:</strong> {{ number_format($order->total) }} تومان</p>
        </div>

        <p style="color: #825B32; margin-bottom: 30px;">
            به زودی با شما تماس می‌گیریم.
        </p>

        <a href="{{ route('products.index') }}">
            <button style="background: #603F26; color: #FFEAC5; border: none; padding: 15px 40px; border-radius: 12px; font-size: 1em; font-weight: bold; cursor: pointer; font-family: Tahoma;">
                بازگشت به فروشگاه
            </button>
        </a>
    </div>
</div>

@endsection