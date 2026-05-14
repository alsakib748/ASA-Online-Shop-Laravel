@extends('front.layouts.app')

@section('title', 'ASA Online Shop - Premium Shopping Experience')

@section('content')

    <!-- Premium Hero Carousel -->
    <section class="premium-hero hero-carousel">
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
            <div class="carousel-indicators hero-indicators">
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"
                    aria-current="true"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
            </div>

            <div class="carousel-inner">
                <!-- Slide 1 -->
                <div class="carousel-item active">
                    <img src="{{ asset('front-assets/images/carousel-1.jpg') }}" alt="Kids Fashion">
                    <div class="hero-overlay"></div>
                    <div class="hero-content">
                        <div class="hero-content-inner">
                            <span class="hero-badge">New Collection</span>
                            <h1 class="hero-title">Kids Fashion</h1>
                            <p class="hero-subtitle">Discover the latest trends for your little ones. Premium quality,
                                comfortable styles.</p>
                            <div class="hero-actions">
                                <a href="{{ route('front.shop') }}" class="btn-premium btn-premium-primary">
                                    Shop Now <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                                <a href="{{ route('front.shop') }}" class="btn-premium btn-premium-outline">
                                    View Collection
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="carousel-item">
                    <img src="{{ asset('front-assets/images/carousel-2.jpg') }}" alt="Women's Fashion">
                    <div class="hero-overlay"></div>
                    <div class="hero-content">
                        <div class="hero-content-inner">
                            <span class="hero-badge">Trending Now</span>
                            <h1 class="hero-title">Women's Fashion</h1>
                            <p class="hero-subtitle">Elegant styles for the modern woman. Express yourself with our curated
                                collection.</p>
                            <div class="hero-actions">
                                <a href="{{ route('front.shop') }}" class="btn-premium btn-premium-primary">
                                    Explore Now <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                                <a href="{{ route('front.shop') }}" class="btn-premium btn-premium-outline">
                                    Learn More
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="carousel-item">
                    <img src="{{ asset('front-assets/images/carousel-2.jpg') }}" alt="Sale">
                    <div class="hero-overlay"></div>
                    <div class="hero-content">
                        <div class="hero-content-inner">
                            <span class="hero-badge" style="background: var(--color-sale);">Up to 70% Off</span>
                            <h1 class="hero-title">Biggest Sale Event</h1>
                            <p class="hero-subtitle">Shop premium branded clothes at unbeatable prices. Limited time only!
                            </p>
                            <div class="hero-actions">
                                <a href="{{ route('front.shop') }}" class="btn-premium btn-premium-accent">
                                    Shop the Sale <i class="fas fa-fire ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <i class="fas fa-chevron-left"></i>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <i class="fas fa-chevron-right"></i>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>

    <!-- Features Bar -->
    <section class="features-bar">
        <div class="container">
            <div class="row g-0">
                <div class="col-md-3">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <div class="feature-content">
                            <h4>Free Shipping</h4>
                            <p>On orders over ৳500</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-undo"></i>
                        </div>
                        <div class="feature-content">
                            <h4>Easy Returns</h4>
                            <p>14-day return policy</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="feature-content">
                            <h4>Secure Payment</h4>
                            <p>100% secure checkout</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <div class="feature-content">
                            <h4>24/7 Support</h4>
                            <p>Dedicated support team</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="section-categories">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Shop by Category</h2>
                <p class="section-subtitle">Explore our curated collection by category</p>
            </div>

            <div class="category-grid">
                @if (getCategories()->isNotEmpty())
                    @foreach (getCategories() as $category)
                        <a href="{{ route('front.shop', $category->slug) }}" class="category-card">
                            @if ($category->image != '')
                                <img src="{{ asset('uploads/category/thumb/' . $category->image) }}"
                                    alt="{{ $category->name }}">
                            @else
                                <img src="{{ asset('admin-assets/img/default-150x150.png') }}"
                                    alt="{{ $category->name }}">
                            @endif
                            <div class="category-overlay">
                                <div class="category-info">
                                    <h3>{{ $category->name }}</h3>
                                    <p>Shop now</p>
                                </div>
                            </div>
                            @if ($category->sub_category->isNotEmpty())
                                <span class="category-count">{{ $category->sub_category->count() }} Subcategories</span>
                            @endif
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="section-products" style="background: var(--color-gray-50);">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Featured Products</h2>
                <p class="section-subtitle">Handpicked selections for the discerning shopper</p>
            </div>

            <div class="product-grid">
                @if ($featuredProducts->isNotEmpty())
                    @foreach ($featuredProducts as $product)
                        @php
                            $productImage = $product->product_images->first();
                        @endphp
                        <div class="product-card">
                            <div class="product-image">
                                <a href="{{ route('front.product', $product->slug) }}">
                                    @if (!empty($productImage->image))
                                        <img src="{{ asset('uploads/product/small/' . $productImage->image) }}"
                                            alt="{{ $product->title }}">
                                    @else
                                        <img src="{{ asset('admin-assets/img/default-150x150.png') }}"
                                            alt="{{ $product->title }}">
                                    @endif
                                </a>

                                <!-- Badges -->
                                @if ($product->compare_price > 0)
                                    <span class="product-badge product-badge-sale">Sale</span>
                                @elseif($product->qty <= 0)
                                    <span class="product-badge product-badge-soldout">Sold Out</span>
                                @endif

                                <!-- Wishlist Button -->
                                <button class="wishlist-btn"
                                    onclick="addToWishList({{ $product->id }}); event.preventDefault();">
                                    <i class="far fa-heart"></i>
                                </button>

                                <!-- Quick Actions -->
                                <div class="product-actions-overlay">
                                    <button class="action-btn" onclick="addToWishList({{ $product->id }})"
                                        title="Add to Wishlist">
                                        <i class="far fa-heart"></i>
                                    </button>
                                    <button class="action-btn action-btn-add"
                                        onclick="quickAddToCart({{ $product->id }})" title="Quick Add">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="product-info">
                                <span class="product-category">{{ $product->category->name ?? '' }}</span>
                                <h3 class="product-title">
                                    <a href="{{ route('front.product', $product->slug) }}">{{ $product->title }}</a>
                                </h3>
                                <div class="product-price">
                                    <span class="price-current">৳{{ number_format($product->price) }}</span>
                                    @if ($product->compare_price > 0)
                                        <span class="price-original">৳{{ number_format($product->compare_price) }}</span>
                                        <span
                                            class="price-discount">{{ round((($product->compare_price - $product->price) / $product->compare_price) * 100) }}%
                                            OFF</span>
                                    @endif
                                </div>
                                <div class="product-rating">
                                    <div class="rating-stars">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= 4 ? '' : 'empty' }}"></i>
                                        @endfor
                                    </div>
                                    <span class="rating-count">({{ $product->product_ratings_count ?? 0 }})</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="text-center mt-5">
                <a href="{{ route('front.shop') }}" class="btn-outline-premium">
                    View All Products <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Latest Products -->
    <section class="section-products">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Latest Products</h2>
                <p class="section-subtitle">Fresh arrivals to keep you on trend</p>
            </div>

            <div class="product-grid">
                @if ($latestProducts->isNotEmpty())
                    @foreach ($latestProducts as $product)
                        @php
                            $productImage = $product->product_images->first();
                        @endphp
                        <div class="product-card">
                            <div class="product-image">
                                <a href="{{ route('front.product', $product->slug) }}">
                                    @if (!empty($productImage->image))
                                        <img src="{{ asset('uploads/product/small/' . $productImage->image) }}"
                                            alt="{{ $product->title }}">
                                    @else
                                        <img src="{{ asset('admin-assets/img/default-150x150.png') }}"
                                            alt="{{ $product->title }}">
                                    @endif
                                </a>

                                <!-- Badges -->
                                @if ($product->compare_price > 0)
                                    <span class="product-badge product-badge-sale">Sale</span>
                                @else
                                    <span class="product-badge product-badge-new">New</span>
                                @endif

                                <!-- Wishlist Button -->
                                <button class="wishlist-btn"
                                    onclick="addToWishList({{ $product->id }}); event.preventDefault();">
                                    <i class="far fa-heart"></i>
                                </button>

                                <!-- Quick Actions -->
                                <div class="product-actions-overlay">
                                    <button class="action-btn" onclick="addToWishList({{ $product->id }})"
                                        title="Add to Wishlist">
                                        <i class="far fa-heart"></i>
                                    </button>
                                    <button class="action-btn action-btn-add"
                                        onclick="quickAddToCart({{ $product->id }})" title="Quick Add">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="product-info">
                                <span class="product-category">{{ $product->category->name ?? '' }}</span>
                                <h3 class="product-title">
                                    <a href="{{ route('front.product', $product->slug) }}">{{ $product->title }}</a>
                                </h3>
                                <div class="product-price">
                                    <span class="price-current">৳{{ number_format($product->price) }}</span>
                                    @if ($product->compare_price > 0)
                                        <span class="price-original">৳{{ number_format($product->compare_price) }}</span>
                                    @endif
                                </div>
                                <div class="product-rating">
                                    <div class="rating-stars">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= 4 ? '' : 'empty' }}"></i>
                                        @endfor
                                    </div>
                                    <span class="rating-count">({{ $product->product_ratings_count ?? 0 }})</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="text-center mt-5">
                <a href="{{ route('front.shop') }}" class="btn-outline-premium">
                    View All Products <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Brands Section -->
    <section class="brands-section py-5">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-primary text-uppercase fw-semibold small ls-2">Trusted By</span>
                <h2 class="fw-bold mt-2">Popular Brands</h2>
                <p class="text-muted">Shop from the world's most trusted brands</p>
            </div>

            @if ($brands->isNotEmpty())
                <div class="row g-4 justify-content-center">
                    @foreach ($brands as $brand)
                        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                            <a href="{{ route('front.shop') }}?brand={{ $brand->id }}" class="brand-card">
                                <div class="brand-logo-wrapper">
                                    @if ($brand->image)
                                        <img src="{{ asset('uploads/brand/' . $brand->image) }}"
                                            alt="{{ $brand->name }}" class="brand-logo">
                                    @else
                                        <span class="brand-name-text">{{ substr($brand->name, 0, 2) }}</span>
                                    @endif
                                </div>
                                <span class="brand-name">{{ $brand->name }}</span>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center text-muted">No brands available</p>
            @endif
        </div>
    </section>

    <!-- Promo Banner -->
    <section class="py-5"
        style="padding: var(--space-3xl) 0; background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-gray-800) 100%);">
        <div class="container text-center">
            <h2 style="font-size: 2.5rem; font-weight: 700; color: var(--color-white); margin-bottom: var(--space-md);">
                Subscribe to Our Newsletter</h2>
            <p
                style="color: var(--color-gray-300); margin-bottom: var(--space-xl); max-width: 500px; margin-left: auto; margin-right: auto;">
                Get exclusive offers and updates straight to your inbox. Join thousands of satisfied customers.</p>
            <form class="d-flex justify-content-center" style="max-width: 450px; margin: 0 auto;">
                <input type="email" placeholder="Enter your email"
                    style="flex: 1; padding: var(--space-md) var(--space-lg); border: none; border-radius: var(--radius-lg) 0 0 var(--radius-lg); outline: none;">
                <button type="submit" class="btn-premium btn-premium-accent"
                    style="border-radius: 0 var(--radius-lg) var(--radius-lg) 0;">
                    Subscribe
                </button>
            </form>
        </div>
    </section>

@endsection
