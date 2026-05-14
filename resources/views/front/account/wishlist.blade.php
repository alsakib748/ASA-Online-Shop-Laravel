@extends('front.layouts.app')

@section('title', 'My Wishlist - ASA Online Shop')

@section('content')

    <!-- Breadcrumb -->
    <section class="py-4" style="background: var(--color-gray-50);">
        <div class="container">
            <nav class="breadcrumb-premium">
                <a href="{{ route('front.home') }}">Home</a>
                <span>/</span>
                <a href="{{ route('dashboard') }}">My Account</a>
                <span>/</span>
                <span class="current">Wishlist</span>
            </nav>
        </div>
    </section>

    <!-- Wishlist Content -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mb-4">
                    @include('front.message')
                </div>

                <div class="col-lg-3 mb-4 mb-lg-0">
                    @include('front.common.sidebar')
                </div>

                <div class="col-lg-9">
                    <div class="checkout-form-card">
                        <h3 class="checkout-title">
                            <i class="fas fa-heart"></i> My Wishlist
                        </h3>

                        @if ($wishlists->isNotEmpty())
                            <div class="d-flex flex-column gap-4">
                                @foreach ($wishlists as $wishlist)
                                    <div class="d-sm-flex justify-content-between align-items-center pb-4"
                                        style="border-bottom: 1px solid var(--color-gray-100);">
                                        <div class="d-flex align-items-start">
                                            <a href="{{ route('front.product', $wishlist->product->slug) }}" class="me-4"
                                                style="width: 100px;">
                                                @php
                                                    $productImage = getProductImage($wishlist->product_id);
                                                @endphp
                                                @if (!empty($productImage))
                                                    <img src="{{ asset('uploads/product/small/' . $productImage->image) }}"
                                                        class="img-fluid" style="border-radius: var(--radius-lg);">
                                                @else
                                                    <img src="{{ asset('admin-assets/img/default-150x150.png') }}"
                                                        class="img-fluid" style="border-radius: var(--radius-lg);">
                                                @endif
                                            </a>
                                            <div>
                                                <h4 class="mb-2">
                                                    <a
                                                        href="{{ route('front.product', $wishlist->product->slug) }}">{{ $wishlist->product->title }}</a>
                                                </h4>
                                                <div class="product-price">
                                                    <span
                                                        class="price-current">৳{{ number_format($wishlist->product->price) }}</span>
                                                    @if ($wishlist->product->compare_price > 0)
                                                        <span
                                                            class="price-original">৳{{ number_format($wishlist->product->compare_price) }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2 mt-3 mt-sm-0">
                                            <button onclick="addToCart({{ $wishlist->product_id }})"
                                                class="btn-primary-premium">
                                                <i class="fas fa-shopping-bag me-2"></i> Add to Cart
                                            </button>
                                            <button onclick="removeProduct({{ $wishlist->product_id }})" class="action-btn"
                                                title="Remove">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-heart" style="font-size: 3rem; color: var(--color-gray-300);"></i>
                                <h4 class="mt-3">Your wishlist is empty</h4>
                                <p class="text-muted mb-4">Save items you love to your wishlist</p>
                                <a href="{{ route('front.shop') }}" class="btn-primary-premium">
                                    <i class="fas fa-arrow-left me-2"></i> Start Shopping
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('customJs')
    <script>
        function removeProduct(id) {
            if (confirm('Are you sure you want to remove this item?')) {
                $.ajax({
                    url: "{{ route('users.removeProductFromWishList') }}",
                    method: 'POST',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == true) {
                            window.location.href = "{{ route('front.wishlist') }}";
                        }
                    }
                });
            }
        }
    </script>
@endsection
