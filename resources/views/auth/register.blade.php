@extends('front.layouts.app')

@section('title', 'Register - ASA Online Shop')

@section('content')

    <!-- Breadcrumb -->
    <section class="py-4" style="background: var(--color-gray-50);">
        <div class="container">
            <nav class="breadcrumb-premium">
                <a href="{{ route('front.home') }}">Home</a>
                <span>/</span>
                <span class="current">Register</span>
            </nav>
        </div>
    </section>

    <!-- Register Section -->
    <section class="py-5" style="background: var(--color-gray-50); min-height: 70vh;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="checkout-form-card">
                        <div class="text-center mb-4">
                            <h3 style="font-weight: 700;">Create Account</h3>
                            <p class="text-muted mb-0">Join us and start shopping</p>
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

                        <form action="{{ route('register') }}" method="POST">
                            @csrf

                            <div class="form-group mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" id="name" name="name" class="form-input"
                                    value="{{ old('name') }}" required autofocus
                                    placeholder="Enter your name">
                                @error('name')
                                    <p class="text-danger small mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" id="email" name="email" class="form-input"
                                    value="{{ old('email') }}" required
                                    placeholder="Enter your email">
                                @error('email')
                                    <p class="text-danger small mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" id="password" name="password" class="form-input"
                                    required autocomplete="new-password"
                                    placeholder="Create a password">
                                @error('password')
                                    <p class="text-danger small mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-input"
                                    required autocomplete="new-password"
                                    placeholder="Confirm your password">
                                @error('password_confirmation')
                                    <p class="text-danger small mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="btn-primary-premium w-100">
                                <i class="fas fa-user-plus me-2"></i> Create Account
                            </button>
                        </form>

                        <div class="text-center mt-4 pt-3" style="border-top: 1px solid var(--color-gray-200);">
                            <p class="text-muted mb-0">Already have an account?
                                <a href="{{ route('login') }}" class="text-primary fw-semibold">Sign in</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection