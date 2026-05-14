@extends('front.layouts.app')

@section('title', 'Login - ASA Online Shop')

@section('content')

    <!-- Breadcrumb -->
    <section class="py-4" style="background: var(--color-gray-50);">
        <div class="container">
            <nav class="breadcrumb-premium">
                <a href="{{ route('front.home') }}">Home</a>
                <span>/</span>
                <span class="current">Login</span>
            </nav>
        </div>
    </section>

    <!-- Login Section -->
    <section class="py-5" style="background: var(--color-gray-50); min-height: 70vh;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="checkout-form-card">
                        <div class="text-center mb-4">
                            <h3 style="font-weight: 700;">Welcome Back</h3>
                            <p class="text-muted mb-0">Sign in to your account</p>
                        </div>

                        @if (Session::has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ Session::get('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if (Session::has('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ Session::get('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" id="email" name="email" class="form-input"
                                    value="{{ old('email') }}" required autofocus
                                    placeholder="Enter your email">
                                @error('email')
                                    <p class="text-danger small mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" id="password" name="password" class="form-input"
                                    required autocomplete="current-password"
                                    placeholder="Enter your password">
                                @error('password')
                                    <p class="text-danger small mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <label class="d-flex align-items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="remember" id="remember" class="form-check-input" style="width: 18px; height: 18px;">
                                    <span class="form-check-label">Remember me</span>
                                </label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-primary fw-semibold" style="font-size: 0.9rem;">
                                        Forgot password?
                                    </a>
                                @endif
                            </div>

                            <button type="submit" class="btn-primary-premium w-100">
                                <i class="fas fa-sign-in-alt me-2"></i> Sign In
                            </button>
                        </form>

                        <div class="text-center mt-4 pt-3" style="border-top: 1px solid var(--color-gray-200);">
                            <p class="text-muted mb-0">Don't have an account?
                                <a href="{{ route('register') }}" class="text-primary fw-semibold">Sign up</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection