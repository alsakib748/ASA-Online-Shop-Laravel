@extends('front.layouts.app')

@section('title', $product->title . ' - ASA Online Shop')

@section('content')

    <!-- Breadcrumb -->
    <section class="py-4" style="background: var(--color-gray-50);">
        <div class="container">
            <nav class="breadcrumb-premium">
                <a href="{{ route('front.home') }}">Home</a>
                <span>/</span>
                <a href="{{ route('front.shop') }}">Shop</a>
                <span>/</span>
                <span class="current">{{ $product->title }}</span>
            </nav>
        </div>
    </section>

    <!-- Product Detail -->
    <section class="product-detail">
        <div class="container">
            <div class="row">
                @include('front.message')

                <!-- Gallery -->
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="product-gallery">
                        <div class="gallery-main">
                            @if ($product->product_images && $product->product_images->isNotEmpty())
                                <img src="{{ asset('uploads/product/large/' . $product->product_images->first()->image) }}" alt="{{ $product->title }}" id="mainImage">
                            @else
                                <img src="{{ asset('admin-assets/img/default-150x150.png') }}" alt="{{ $product->title }}">
                            @endif
                        </div>
                        @if ($product->product_images && $product->product_images->count() > 1)
                            <div class="gallery-thumbs">
                                @foreach ($product->product_images as $key => $productImage)
                                    <div class="thumb-item {{ $key == 0 ? 'active' : '' }}" onclick="changeImage('{{ asset('uploads/product/large/' . $productImage->image) }}')">
                                        <img src="{{ asset('uploads/product/small/' . $productImage->image) }}" alt="">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Product Info -->
                <div class="col-lg-6">
                    <div class="product-detail-info">
                        <h1 class="product-detail-title">{{ $product->title }}</h1>

                        <div class="product-detail-rating">
                            <div class="star-rating" title="">
                                <div class="back-stars">
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <div class="front-stars" style="width: {{ $avgRatingPer }}%">
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                            <span class="text-muted">({{ $product->product_ratings_count }} {{ $product->product_ratings_count == 1 ? 'Review' : 'Reviews' }})</span>
                        </div>

                        <div class="product-detail-price">
                            <span class="price-lg">৳{{ number_format($product->price) }}</span>
                            @if ($product->compare_price > 0)
                                <span class="price-lg-original">৳{{ number_format($product->compare_price) }}</span>
                                <span class="price-discount ms-2">{{ round((($product->compare_price - $product->price) / $product->compare_price) * 100) }}% OFF</span>
                            @endif
                        </div>

                        <p class="product-detail-desc">
                            {!! $product->short_description !!}
                        </p>

                        <!-- Stock Status -->
                        <div class="mb-4">
                            @if ($product->track_qty == 'Yes')
                                @if ($product->qty > 0)
                                    <span class="d-inline-flex align-items-center gap-2">
                                        <i class="fas fa-check-circle" style="color: var(--color-success);"></i>
                                        <span class="text-success fw-semibold">In Stock ({{ $product->qty }} available)</span>
                                    </span>
                                @else
                                    <span class="d-inline-flex align-items-center gap-2">
                                        <i class="fas fa-times-circle" style="color: var(--color-danger);"></i>
                                        <span class="text-danger fw-semibold">Out of Stock</span>
                                    </span>
                                @endif
                            @else
                                <span class="d-inline-flex align-items-center gap-2">
                                    <i class="fas fa-check-circle" style="color: var(--color-success);"></i>
                                    <span class="text-success fw-semibold">In Stock</span>
                                </span>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="detail-actions">
                            @if ($product->track_qty == 'Yes')
                                @if ($product->qty > 0)
                                    <button class="btn-primary-premium flex-fill" onclick="addToCart({{ $product->id }})">
                                        <i class="fas fa-shopping-bag me-2"></i> Add to Cart
                                    </button>
                                @else
                                    <button class="btn-primary-premium flex-fill" disabled style="opacity: 0.5; cursor: not-allowed;">
                                        <i class="fas fa-shopping-bag me-2"></i> Out of Stock
                                    </button>
                                @endif
                            @else
                                <button class="btn-primary-premium flex-fill" onclick="addToCart({{ $product->id }})">
                                    <i class="fas fa-shopping-bag me-2"></i> Add to Cart
                                </button>
                            @endif

                            <button class="action-btn" onclick="addToWishList({{ $product->id }})" title="Add to Wishlist">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>

                        <!-- Product Meta -->
                        <div class="product-meta">
                            <div class="meta-item">
                                <span class="meta-label">Category:</span>
                                <span class="meta-value">{{ $product->category->name ?? 'N/A' }}</span>
                            </div>
                            @if($product->brand)
                            <div class="meta-item">
                                <span class="meta-label">Brand:</span>
                                <span class="meta-value">{{ $product->brand->name ?? 'N/A' }}</span>
                            </div>
                            @endif
                            <div class="meta-item">
                                <span class="meta-label">SKU:</span>
                                <span class="meta-value">{{ $product->sku ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Tabs -->
                <div class="col-12 mt-5">
                    <div class="product-tabs">
                        <div class="tabs-nav">
                            <button class="tab-btn active" onclick="openTab(event, 'description')">Description</button>
                            <button class="tab-btn" onclick="openTab(event, 'shipping')">Shipping & Returns</button>
                            <button class="tab-btn" onclick="openTab(event, 'reviews')">Reviews ({{ $product->product_ratings_count }})</button>
                        </div>

                        <div class="tab-content active" id="description">
                            {!! $product->description !!}
                        </div>

                        <div class="tab-content" id="shipping">
                            {!! $product->shipping_returns !!}
                        </div>

                        <div class="tab-content" id="reviews">
                            <div class="row">
                                <!-- Write Review Form -->
                                <div class="col-lg-6 mb-5">
                                    <h4 class="mb-4">Write a Review</h4>
                                    <form action="" method="post" name="productRatingForm" id="productRatingForm">
                                        <div class="mb-3">
                                            <label class="form-label">Your Name</label>
                                            <input type="text" class="form-input" name="name" id="name" placeholder="Your Name">
                                            <p class="text-danger small mt-1" id="nameError"></p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-input" name="email" id="email" placeholder="Your Email">
                                            <p class="text-danger small mt-1" id="emailError"></p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Rating</label>
                                            <div class="d-flex gap-2">
                                                @for($i = 5; $i >= 1; $i--)
                                                <input type="radio" name="rating" value="{{ $i }}" id="rating-{{ $i }}">
                                                <label for="rating-{{ $i }}" class="cursor-pointer">
                                                    <i class="fas fa-star text-warning" style="font-size: 1.5rem;"></i>
                                                </label>
                                                @endfor
                                            </div>
                                            <p class="text-danger small mt-1" id="ratingError"></p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Your Review</label>
                                            <textarea name="comment" id="comment" class="form-input" cols="30" rows="4" placeholder="Share your experience..."></textarea>
                                            <p class="text-danger small mt-1" id="commentError"></p>
                                        </div>
                                        <button type="submit" class="btn-primary-premium">Submit Review</button>
                                    </form>
                                </div>

                                <!-- Reviews List -->
                                <div class="col-lg-6">
                                    <h4 class="mb-4">Customer Reviews</h4>

                                    <!-- Overall Rating -->
                                    <div class="d-flex align-items-center gap-4 mb-4 p-4" style="background: var(--color-gray-50); border-radius: var(--radius-lg);">
                                        <div class="text-center">
                                            <div class="h1 fw-bold mb-0" style="font-size: 3rem;">{{ number_format($avgRating, 1) }}</div>
                                            <div class="rating-stars d-flex justify-content-center">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= round($avgRating) ? '' : 'empty' }}"></i>
                                                @endfor
                                            </div>
                                            <small class="text-muted">{{ $product->product_ratings_count }} reviews</small>
                                        </div>
                                    </div>

                                    @if ($product->product_ratings->isNotEmpty())
                                        @foreach ($product->product_ratings as $rating)
                                            @php
                                                $ratingPer = ($rating->rating * 100) / 5;
                                            @endphp
                                            <div class="border-bottom pb-4 mb-4">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <div>
                                                        <strong>{{ $rating->username }}</strong>
                                                        <div class="rating-stars">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <i class="fas fa-star {{ $i <= $rating->rating ? '' : 'empty' }}"></i>
                                                            @endfor
                                                        </div>
                                                    </div>
                                                    <small class="text-muted">{{ $rating->created_at->format('M d, Y') }}</small>
                                                </div>
                                                <p class="text-muted mb-0">{{ $rating->comment }}</p>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted">No reviews yet. Be the first to review this product!</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Products -->
    @if (!empty($relatedProducts) && $relatedProducts->isNotEmpty())
        <section class="section-products" style="background: var(--color-gray-50);">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Related Products</h2>
                    <p class="section-subtitle">You might also like</p>
                </div>

                <div class="product-grid">
                    @foreach ($relatedProducts as $relProduct)
                        @php
                            $productImage = $relProduct->product_images->first();
                        @endphp
                        <div class="product-card">
                            <div class="product-image">
                                <a href="{{ route('front.product', $relProduct->slug) }}">
                                    @if (!empty($productImage->image))
                                        <img src="{{ asset('uploads/product/small/' . $productImage->image) }}" alt="{{ $relProduct->title }}">
                                    @else
                                        <img src="{{ asset('admin-assets/img/default-150x150.png') }}" alt="{{ $relProduct->title }}">
                                    @endif
                                </a>

                                @if($relProduct->compare_price > 0)
                                    <span class="product-badge product-badge-sale">Sale</span>
                                @endif

                                <button class="wishlist-btn" onclick="addToWishList({{ $relProduct->id }}); event.preventDefault();">
                                    <i class="far fa-heart"></i>
                                </button>

                                <div class="product-actions-overlay">
                                    <button class="action-btn action-btn-add" onclick="quickAddToCart({{ $relProduct->id }})">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="product-info">
                                <h3 class="product-title">
                                    <a href="{{ route('front.product', $relProduct->slug) }}">{{ $relProduct->title }}</a>
                                </h3>
                                <div class="product-price">
                                    <span class="price-current">৳{{ number_format($relProduct->price) }}</span>
                                    @if ($relProduct->compare_price > 0)
                                        <span class="price-original">৳{{ number_format($relProduct->compare_price) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection

@section('customJs')
    <script>
        // Image gallery
        function changeImage(src) {
            document.getElementById('mainImage').src = src;
            document.querySelectorAll('.thumb-item').forEach(item => {
                item.classList.remove('active');
            });
            event.currentTarget.classList.add('active');
        }

        // Tabs
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tab-content");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
                tabcontent[i].classList.remove('active');
            }
            tablinks = document.getElementsByClassName("tab-btn");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(tabName).style.display = "block";
            document.getElementById(tabName).classList.add('active');
            evt.currentTarget.classList.add("active");
        }

        // Rating form submission
        $("#productRatingForm").submit(function(event) {
            event.preventDefault();

            $.ajax({
                url: "{{ route('front.saveRating', $product->id) }}",
                type: 'POST',
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function(response) {
                    if (response.status == false) {
                        var errors = response.errors;

                        if (errors.name) {
                            $("#name").addClass("is-invalid");
                            $("#nameError").html(errors.name);
                        } else {
                            $("#name").removeClass("is-invalid");
                            $("#nameError").html("");
                        }

                        if (errors.email) {
                            $("#email").addClass("is-invalid");
                            $("#emailError").html(errors.email);
                        } else {
                            $("#email").removeClass("is-invalid");
                            $("#emailError").html("");
                        }

                        if (errors.comment) {
                            $("#comment").addClass("is-invalid");
                            $("#commentError").html(errors.comment);
                        } else {
                            $("#comment").removeClass("is-invalid");
                            $("#commentError").html("");
                        }

                        if (errors.rating) {
                            $("#ratingError").html(errors.rating);
                        } else {
                            $("#ratingError").html("");
                        }
                    } else {
                        window.location.href = "{{ route('front.product', $product->slug) }}";
                    }
                }
            });
        });
    </script>
@endsection