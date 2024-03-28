{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Login</h2>
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" required autofocus>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember">Remember Me</label>
        </div>

        <button type="submit" class="btn btn-primary">Login</button>

        @if (Route::has('password.request'))
            <a class="btn btn-link" href="{{ route('password.request') }}">Forgot Your Password?</a>
        @endif

        @if (Route::has('register'))
            <a class="btn btn-link" href="{{ route('register') }}">Register</a>
        @endif
    </form>
</div>
@endsection