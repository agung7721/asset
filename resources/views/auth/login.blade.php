@extends('adminlte::auth.login')

@section('title', 'Login')

@section('auth_header')
    <div class="text-center mb-3">
        <img src="{{ asset('images/logo-kurhanz.png') }}" alt="Kurhanz Trans Logo" style="max-width:200px;max-height:90px;margin-bottom:18px;">
        <h2 class="font-weight-bold" style="color:#222;">Asset Management System</h2>
    </div>
    
@endsection

@section('auth_body')
    <form action="{{ route('login') }}" method="post">
        @csrf

        {{-- Email field --}}
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" placeholder="Email" autofocus>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Password field --}}
        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                   placeholder="Password">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Remember Me --}}
        <div class="row">
            <div class="col-8">
                <div class="icheck-primary">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember">Remember Me</label>
                </div>
            </div>
            <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            </div>
        </div>
    </form>
@stop

@section('auth_footer')
    <p class="my-0">
        <a href="{{ route('password.request') }}">
            Forgot Your Password?
        </a>
    </p>
@stop

@section('css')
<style>
    .login-page {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .login-card-body {
        border-radius: 15px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        background: white;
        padding: 2rem;
    }
    
    .login-logo {
        font-weight: bold;
        color: #3c8dbc;
    }
    
    .login-box {
        width: 100%;
        max-width: 400px;
    }
    
    .form-control {
        border-radius: 8px;
        border: 1px solid #ddd;
        padding: 12px 15px;
        font-size: 14px;
    }
    
    .form-control:focus {
        border-color: #3c8dbc;
        box-shadow: 0 0 0 0.2rem rgba(60, 141, 188, 0.25);
    }
    
    .btn-primary {
        background-color: #3c8dbc;
        border-color: #3c8dbc;
        border-radius: 8px;
        padding: 12px;
        font-weight: 600;
    }
    
    .btn-primary:hover {
        background-color: #367fa9;
        border-color: #367fa9;
    }
    
    .input-group-text {
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 0 8px 8px 0;
    }
    
    .icheck-primary input[type="checkbox"]:checked + label::before {
        background-color: #3c8dbc;
        border-color: #3c8dbc;
    }
    
    .auth-footer a {
        color: #3c8dbc;
        text-decoration: none;
    }
    
    .auth-footer a:hover {
        text-decoration: underline;
    }
</style>
@stop
