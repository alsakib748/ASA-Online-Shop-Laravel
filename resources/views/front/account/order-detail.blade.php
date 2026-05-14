@extends('front.layouts.app')

@section('title', 'Order Details - ASA Online Shop')

@section('content')

    <!-- Breadcrumb -->
    <section class="py-4" style="background: var(--color-gray-50);">
        <div class="container">
            <nav class="breadcrumb-premium">
                <a href="{{ route('front.home') }}">Home</a>
                <span>/</span>
                <a href="{{ route('dashboard') }}">My Account</a>
                <span>/</span>
                <a href="{{ route('front.orders') }}">Orders</a>
                <span>/</span>
                <span class="current">#{{ $order->id }}</span>
            </nav>
        </div>
    </section>

    <!-- Order Detail Content -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 mb-4 mb-lg-0">
                    @include('front.common.sidebar')
                </div>

                <div class="col-lg-9">
                    <div class="checkout-form-card mb-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                            <h3 class="checkout-title mb-0">
                                <i class="fas fa-shopping-bag"></i> Order #{{ $order->id }}
                            </h3>
                            <span class="badge"
                                style="background: var(--color-success); padding: var(--space-sm) var(--space-md);">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>

                        <!-- Order Info -->
                        <div class="row mb-4 pb-4" style="border-bottom: 1px solid var(--color-gray-100);">
                            <div class="col-6 col-md-3">
                                <h6 class="text-muted small">Order Date</h6>
                                <p class="fw-semibold mb-0">
                                    {{ \Carbon\Carbon::parse($order->created_at)->format('d M, Y') }}</p>
                            </div>
                            <div class="col-6 col-md-3">
                                <h6 class="text-muted small">Subtotal</h6>
                                <p class="fw-semibold mb-0">৳{{ number_format($order->subtotal, 2) }}</p>
                            </div>
                            <div class="col-6 col-md-3">
                                <h6 class="text-muted small">Shipping</h6>
                                <p class="fw-semibold mb-0">৳{{ number_format($order->shipping, 2) }}</p>
                            </div>
                            <div class="col-6 col-md-3">
                                <h6 class="text-muted small">Total</h6>
                                <p class="fw-bold mb-0" style="font-size: 1.25rem;">
                                    ৳{{ number_format($order->grand_total, 2) }}</p>
                            </div>
                        </div>

                        <!-- Products -->
                        <h4 class="mb-3">Order Items</h4>
                        @foreach ($order->order_items as $item)
                            @php
                                $product = $item->product;
                                // dd($product);

                                $productImage = $product?->product_images->first();
                                $productImagePath = null;

                                if ($productImage?->image) {
                                    $smallImagePath = public_path('uploads/product/small/' . $productImage->image);
                                    $largeImagePath = public_path('uploads/product/large/' . $productImage->image);

                                    $productImagePath = file_exists($smallImagePath)
                                        ? asset('uploads/product/small/' . $productImage->image)
                                        : (file_exists($largeImagePath)
                                            ? asset('uploads/product/large/' . $productImage->image)
                                            : null);
                                }
                            @endphp
                            <div class="d-flex align-items-center gap-3 pb-3 mb-3"
                                style="border-bottom: 1px solid var(--color-gray-100);">
                                <div
                                    style="width: 60px; height: 60px; background: var(--color-gray-100); border-radius: var(--radius-md); overflow: hidden;">
                                    @if ($productImagePath)
                                        <img src="{{ $productImagePath }}" class="img-fluid" alt="{{ $item->name }}">
                                    @else
                                        <img src="{{ asset('admin-assets/img/default-150x150.png') }}" class="img-fluid"
                                            alt="{{ $item->name }}">
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-1">{{ $item->name }}</h5>
                                    <p class="text-muted mb-0 small">Qty: {{ $item->qty }} x
                                        ৳{{ number_format($item->price) }}</p>
                                </div>
                                <div class="fw-semibold">৳{{ number_format($item->qty * $item->price) }}</div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Shipping Address -->
                    <div class="checkout-form-card">
                        <h4 class="mb-3">
                            <i class="fas fa-map-marker-alt me-2"></i> Shipping Address
                        </h4>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>{{ $order->first_name }} {{ $order->last_name }}</strong></p>
                                <p class="text-muted mb-0">{{ $order->address }}</p>
                                @if ($order->apartment)
                                    <p class="text-muted mb-0">{{ $order->apartment }}</p>
                                @endif
                                <p class="text-muted mb-0">{{ $order->city }}, {{ $order->state }} {{ $order->zip }}
                                </p>
                                <p class="text-muted mb-0">{{ $order->country?->name ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Contact</strong></p>
                                <p class="text-muted mb-0">{{ $order->email }}</p>
                                <p class="text-muted mb-0">{{ $order->mobile }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
