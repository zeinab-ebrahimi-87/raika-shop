@extends('layouts.app')

@section('title', 'ثبت‌نام | رایکا')

@section('content')
<div class="auth-box">
    <h1>ثبت‌نام</h1>
    
    <form method="POST" action="/register">
        @csrf
        
        <div class="form-group">
            <label>نام کامل:</label>
            <input type="text" name="name" value="{{ old('name') }}" required>
        </div>
        
        <div class="form-group">
            <label>ایمیل:</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
        </div>
        
        <div class="form-group">
            <label>رمز عبور:</label>
            <input type="password" name="password" required>
            <small>حداقل ۸ کاراکتر</small>
        </div>
        
        <div class="form-group">
            <label>تکرار رمز عبور:</label>
            <input type="password" name="password_confirmation" required>
        </div>
        
        <button type="submit" class="btn-submit">ثبت‌نام</button>
    </form>
    
    <p class="link-bottom">
        حساب داری؟ <a href="/login">وارد شو</a>
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
        font-family: IRANSans,Tahoma, sans-serif;
        font-size: 1em;
        box-sizing: border-box;
    }
    .form-group input:focus {
        outline: none;
        border-color: #603F26;
        box-shadow: 0 0 0 3px rgba(96, 63, 38, 0.2);
    }
    .form-group small {
        color: #825B32;
        font-size: 0.85em;
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
        font-family: Tahoma, sans-serif;
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