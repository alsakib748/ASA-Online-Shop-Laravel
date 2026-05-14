@extends('front.layouts.app')

@section('title', 'Thank You - ASA Online Shop')

@section('content')

    <!-- Breadcrumb -->
    <section class="py-4" style="background: var(--color-gray-50);">
        <div class="container">
            <nav class="breadcrumb-premium">
                <a href="{{ route('front.home') }}">Home</a>
                <span>/</span>
                <span class="current">Thank You</span>
            </nav>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="checkout-form-card text-center" style="max-width: 500px; margin: 0 auto;">
                @if(Session::has('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        {{ Session::get("success") }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="mb-4">
                    <i class="fas fa-check-circle" style="font-size: 4rem; color: var(--color-success);"></i>
                </div>

                <h1 class="mb-3">Thank You!</h1>
                <p class="text-muted mb-4">Your order has been placed successfully.</p>

                <div class="p-4" style="background: var(--color-gray-50); border-radius: var(--radius-lg);">
                    <p class="mb-1">Order ID:</p>
                    <h3 class="text-primary">{{ $id }}</h3>
                </div>

                <div class="mt-4">
                    <a href="{{ route('front.shop') }}" class="btn-primary-premium">
                        <i class="fas fa-shopping-bag me-2"></i> Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection