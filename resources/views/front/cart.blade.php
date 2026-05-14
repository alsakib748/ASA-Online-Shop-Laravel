@extends('front.layouts.app')

@section('title', 'Shopping Cart - ASA Online Shop')

@section('content')

    <!-- Breadcrumb -->
    <section class="py-4" style="background: var(--color-gray-50);">
        <div class="container">
            <nav class="breadcrumb-premium">
                <a href="{{ route('front.home') }}">Home</a>
                <span>/</span>
                <a href="{{ route('front.shop') }}">Shop</a>
                <span>/</span>
                <span class="current">Cart</span>
            </nav>
        </div>
    </section>

    <!-- Cart Content -->
    <section class="cart-page">
        <div class="container">
            <div class="row">
                <!-- Cart Items -->
                <div class="col-lg-8 mb-4 mb-lg-0">
                    @if (Session::has('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert"
                            style="border-radius: var(--radius-lg);">
                            {!! Session::get('success') !!}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (Session::has('error'))
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert"
                            style="border-radius: var(--radius-lg);">
                            {{ Session::get('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (Cart::count() > 0)
                        <div class="cart-table-wrapper">
                            <table class="cart-table">
                                <thead>
                                    <tr>
                                        <th style="width: 40%;">Product</th>
                                        <th style="width: 20%;">Price</th>
                                        <th style="width: 20%;">Quantity</th>
                                        <th style="width: 15%;">Total</th>
                                        <th style="width: 5%;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!empty($cartContent))
                                        @foreach ($cartContent as $item)
                                            <tr>
                                                <td>
                                                    <div class="cart-product">
                                                        <div class="cart-product-image">
                                                            @if (!empty($item->options->productImage->image))
                                                                <img
                                                                    src="{{ asset('uploads/product/small/' . $item->options->productImage->image) }}">
                                                            @else
                                                                <img
                                                                    src="{{ asset('admin-assets/img/default-150x150.png') }}">
                                                            @endif
                                                        </div>
                                                        <div class="cart-product-info">
                                                            <h4>{{ $item->name }}</h4>
                                                            @if ($item->options->size)
                                                                <p>Size: {{ $item->options->size }}</p>
                                                            @endif
                                                            @if ($item->options->color)
                                                                <p>Color: {{ $item->options->color }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="cart-price">৳{{ number_format($item->price) }}</td>
                                                <td>
                                                    <div class="cart-qty">
                                                        <button class="sub" data-id="{{ $item->rowId }}"><i
                                                                class="fas fa-minus"></i></button>
                                                        <input type="text" value="{{ $item->qty }}" readonly>
                                                        <button class="add" data-id="{{ $item->rowId }}"><i
                                                                class="fas fa-plus"></i></button>
                                                    </div>
                                                </td>
                                                <td class="cart-total">৳{{ number_format($item->price * $item->qty) }}</td>
                                                <td>
                                                    <button class="cart-remove"
                                                        onclick="deleteItem('{{ $item->rowId }}')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-cart">
                            <i class="fas fa-shopping-bag"></i>
                            <h3>Your cart is empty</h3>
                            <p>Looks like you haven't added anything to your cart yet.</p>
                            <a href="{{ route('front.shop') }}" class="btn-primary-premium">
                                <i class="fas fa-arrow-left me-2"></i> Continue Shopping
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Cart Summary -->
                @if (Cart::count() > 0)
                    <div class="col-lg-4">
                        <div class="cart-summary">
                            <h3 class="summary-title">Order Summary</h3>

                            <div class="summary-row">
                                <span>Subtotal ({{ Cart::count() }} items)</span>
                                <span>৳{{ Cart::subtotal() }}</span>
                            </div>

                            <div class="summary-row">
                                <span>Shipping</span>
                                <span class="text-muted">Calculated at checkout</span>
                            </div>

                            <div class="summary-row">
                                <span>Tax</span>
                                <span class="text-muted">Calculated at checkout</span>
                            </div>

                            <div class="summary-row total">
                                <span>Estimated Total</span>
                                <span>৳{{ Cart::subtotal() }}</span>
                            </div>

                            <a href="{{ route('front.checkout') }}" class="btn-primary-premium w-100 mt-4"
                                style="margin-bottom: 30px;">
                                Proceed to Checkout <i class="fas fa-arrow-right ms-2"></i>
                            </a>

                            <a href="{{ route('front.shop') }}" class="btn-outline-premium w-100 mt-3"
                                style="text-align: center;">
                                Continue Shopping
                            </a>

                            <!-- Coupon -->
                            <div class="coupon-form">
                                <input type="text" placeholder="Enter coupon code">
                                <button type="button">Apply</button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

@endsection

@section('customJs')
    <script>
        // Add quantity
        $('.add').click(function() {
            var qtyElement = $(this).parent().prev();
            var qtyValue = parseInt(qtyElement.val());
            if (qtyValue < 10) {
                qtyElement.val(qtyValue + 1);
                var rowId = $(this).data('id');
                var newQty = qtyElement.val();
                updateCart(rowId, newQty);
            }
        });

        // Subtract quantity
        $('.sub').click(function() {
            var qtyElement = $(this).parent().next();
            var qtyValue = parseInt(qtyElement.val());
            if (qtyValue > 1) {
                qtyElement.val(qtyValue - 1);
                var rowId = $(this).data('id');
                var newQty = qtyElement.val();
                updateCart(rowId, newQty);
            }
        });

        function updateCart(rowId, qty) {
            $.ajax({
                url: '{{ route('front.updateCart') }}',
                type: 'post',
                data: {
                    rowId: rowId,
                    qty: qty
                },
                dataType: 'json',
                success: function(response) {
                    window.location.href = '{{ route('front.cart') }}';
                }
            });
        }

        function deleteItem(rowId) {
            if (confirm("Are you sure you want to remove this item?")) {
                $.ajax({
                    url: '{{ route('front.deleteItem.cart') }}',
                    type: 'post',
                    data: {
                        rowId: rowId
                    },
                    dataType: 'json',
                    success: function(response) {
                        window.location.href = '{{ route('front.cart') }}';
                    }
                });
            }
        }
    </script>
@endsection
