@extends('front.layouts.app')

@section('title', 'My Orders - ASA Online Shop')

@section('content')

    <!-- Breadcrumb -->
    <section class="py-4" style="background: var(--color-gray-50);">
        <div class="container">
            <nav class="breadcrumb-premium">
                <a href="{{ route('front.home') }}">Home</a>
                <span>/</span>
                <a href="{{ route('dashboard') }}">My Account</a>
                <span>/</span>
                <span class="current">Orders</span>
            </nav>
        </div>
    </section>

    <!-- Orders Content -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 mb-4 mb-lg-0">
                    @include('front.common.sidebar')
                </div>

                <div class="col-lg-9">
                    <div class="checkout-form-card">
                        <h3 class="checkout-title">
                            <i class="fas fa-shopping-bag"></i> My Orders
                        </h3>

                        <div class="table-responsive">
                            <table class="cart-table">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($orders->isNotEmpty())
                                        @foreach ($orders as $order)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('users.orderDetail', $order->id) }}" class="fw-semibold">#{{ $order->id }}</a>
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d M, Y') }}</td>
                                                <td>
                                                    @if ($order->status == 'pending')
                                                        <span class="badge" style="background: var(--color-warning);">Pending</span>
                                                    @elseif($order->status == 'shipped')
                                                        <span class="badge" style="background: var(--color-accent);">Shipped</span>
                                                    @elseif($order->status == 'cancelled')
                                                        <span class="badge" style="background: var(--color-danger);">Cancelled</span>
                                                    @else
                                                        <span class="badge" style="background: var(--color-success);">Delivered</span>
                                                    @endif
                                                </td>
                                                <td class="fw-semibold">৳{{ number_format($order->grand_total, 2) }}</td>
                                                <td>
                                                    <a href="{{ route('users.orderDetail', $order->id) }}" class="btn-secondary-premium btn-sm">
                                                        View
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <i class="fas fa-shopping-bag" style="font-size: 2rem; color: var(--color-gray-300);"></i>
                                                <p class="mt-2 text-muted">No orders yet</p>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection