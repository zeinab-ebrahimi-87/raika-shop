@extends('layouts.app')

@section('title', 'ورود | رایکا')

@section('content')
<div class="auth-box">
    <h1>ورود</h1>
    
    <form method="POST" action="/login">
        @csrf
        
        <div class="form-group">
            <label>ایمیل:</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
        </div>
        
        <div class="form-group">
            <label>رمز عبور:</label>
            <input type="password" name="password" required>
        </div>
        
        <div class="form-group" style="display: flex; align-items: center; gap: 8px;">
            <input type="checkbox" name="remember" id="remember" style="width: auto;">
            <label for="remember" style="margin: 0;">مرا به خاطر بسپار</label>
        </div>
        
        <button type="submit" class="btn-submit">ورود</button>
    </form>
    
    <p class="link-bottom">
        حساب نداری؟ <a href="/register">ثبت‌نام کن</a>
    </p>
</div>

<style>
    .auth-box {
        max-width: 450px;
        margin: 40px auto;
        background: rgba(255, 255, 255, 0.9);
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    .auth-box h1 {
        text-align: center;
        color: #603F26;
        margin-bottom: 30px;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        color: #603F26;
        margin-bottom: 8px;
        font-weight: bold;
    }
    .form-group input {
        width: 100%;
        padding: 12px;
        border: 2px solid #825B32;
        border-radius: 10px;
        font-family: Tahoma, sans-serif;
        font-size: 1em;
        box-sizing: border-box;
    }
    .form-group input[type="checkbox"] {
        width: auto;
    }
    .form-group input:focus {
        outline: none;
        border-color: #603F26;
        box-shadow: 0 0 0 3px rgba(96, 63, 38, 0.2);
    }
    .btn-submit {
        width: 100%;
        padding: 15px;
        background: #603F26;
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 1.1em;
        font-weight: bold;
        cursor: pointer;
        font-family: IRANSans,Tahoma, sans-serif;
        transition: 0.3s;
    }
    .btn-submit:hover {
        background: #825B32;
    }
    .error-box {
        background: #ffebee;
        border-right: 4px solid #d32f2f;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .error-box p {
        color: #d32f2f;
        margin: 5px 0;
    }
    .link-bottom {
        text-align: center;
        margin-top: 20px;
        color: #825B32;
    }
    .link-bottom a {
        color: #603F26;
        font-weight: bold;
    }
</style>
@endsection