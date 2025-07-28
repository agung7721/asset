@extends('adminlte::auth.login')

@section('title', 'Login')

@section('auth_header')
    <div class="text-center mb-3">
        <img src="/images/logo-kurhanz.png" alt="Kurhanz Trans Logo" style="max-width:200px;max-height:90px;margin-bottom:18px;">
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
        background: #f4f6f9;
    }
    .login-card-body {
        border-radius: 10px;
    }
    .login-logo {
        font-weight: bold;
        color: #3c8dbc;
    }
</style>
@stop
