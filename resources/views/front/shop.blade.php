@extends('front.layouts.app')

@section('title', 'Shop - ASA Online Shop')

@section('content')

    <!-- Breadcrumb -->
    <section class="py-4" style="background: var(--color-gray-50);">
        <div class="container">
            <nav class="breadcrumb-premium">
                <a href="{{ route('front.home') }}">Home</a>
                <span>/</span>
                <span class="current">Shop</span>
            </nav>
        </div>
    </section>

    <!-- Shop Content -->
    <section class="product-detail">
        <div class="container">
            <div class="row">
                <!-- Sidebar Filters -->
                <div class="col-lg-3 mb-5 mb-lg-0">
                    <!-- Categories -->
                    <div class="checkout-form-card mb-4">
                        <h4 class="checkout-title">
                            <i class="fas fa-th-large"></i> Categories
                        </h4>
                        <div class="accordion accordion-flush" id="categoryAccordion">
                            @if ($categories->isNotEmpty())
                                @foreach ($categories as $key => $category)
                                    <div class="accordion-item border-0">
                                        @if ($category->sub_category->isNotEmpty())
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed fw-semibold" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#category-{{ $key }}"
                                                    aria-expanded="false">
                                                    {{ $category->name }}
                                                </button>
                                            </h2>
                                            <div id="category-{{ $key }}"
                                                class="accordion-collapse collapse {{ $categorySelected == $category->id ? 'show' : '' }}">
                                                <div class="accordion-body p-0">
                                                    @foreach ($category->sub_category as $subCategory)
                                                        <a href="{{ route('front.shop', [$category->slug, $subCategory->slug]) }}"
                                                            class="d-block py-2 ps-3 text-muted {{ $subCategorySelected == $subCategory->id ? 'text-primary fw-semibold' : '' }}"
                                                            style="border-bottom: 1px solid var(--color-gray-100);">
                                                            {{ $subCategory->name }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @else
                                            <a href="{{ route('front.shop', $category->slug) }}"
                                                class="d-block py-3 fw-semibold {{ $categorySelected == $category->id ? 'text-primary' : 'text-muted' }}"
                                                style="border-bottom: 1px solid var(--color-gray-100);">
                                                {{ $category->name }}
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <!-- Brands -->
                    <div class="checkout-form-card mb-4">
                        <h4 class="checkout-title">
                            <i class="fas fa-tag"></i> Brands
                        </h4>
                        <div class="d-flex flex-column gap-2">
                            @if ($brands->isNotEmpty())
                                @foreach ($brands as $brand)
                                    <label class="d-flex align-items-center gap-2 cursor-pointer">
                                        <input {{ in_array($brand->id, $brandsArray) ? 'checked' : '' }}
                                            class="brand-label form-check-input" type="checkbox"
                                            value="{{ $brand->id }}" style="width: 18px; height: 18px;">
                                        <span class="form-check-label">{{ $brand->name }}</span>
                                    </label>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div class="checkout-form-card">
                        <h4 class="checkout-title">
                            <i class="fas fa-dollar-sign"></i> Price Range
                        </h4>
                        <input type="text" class="js-range-slider" name="my_range" value="" />
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="col-lg-9">
                    <!-- Toolbar -->
                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                        <p class="mb-0 text-muted">Showing <strong>{{ $products->count() }}</strong> products</p>
                        <div class="d-flex align-items-center gap-3">
                            <select name="sort" id="sort" class="form-select" style="width: auto; padding: var(--space-sm) var(--space-lg); border-radius: var(--radius-lg);">
                                <option selected value="">Sort By</option>
                                <option {{ $sort == 'latest' ? 'selected' : '' }} value="latest">Newest First</option>
                                <option {{ $sort == 'price_asc' ? 'selected' : '' }} value="price_asc">Price: Low to High</option>
                                <option {{ $sort == 'price_desc' ? 'selected' : '' }} value="price_desc">Price: High to Low</option>
                            </select>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div class="row g-4">
                        @if ($products->isNotEmpty())
                            @foreach ($products as $product)
                                @php
                                    $productImage = $product->product_images->first();
                                @endphp
                                <div class="col-6 col-md-4">
                                    <div class="product-card">
                                        <div class="product-image">
                                            <a href="{{ route('front.product', $product->slug) }}">
                                                @if (!empty($productImage->image))
                                                    <img src="{{ asset('uploads/product/small/' . $productImage->image) }}" alt="{{ $product->title }}">
                                                @else
                                                    <img src="{{ asset('admin-assets/img/default-150x150.png') }}" alt="{{ $product->title }}">
                                                @endif
                                            </a>

                                            @if($product->compare_price > 0)
                                                <span class="product-badge product-badge-sale">Sale</span>
                                            @elseif($product->qty <= 0)
                                                <span class="product-badge product-badge-soldout">Sold Out</span>
                                            @endif

                                            <button class="wishlist-btn" onclick="addToWishList({{ $product->id }}); event.preventDefault();">
                                                <i class="far fa-heart"></i>
                                            </button>

                                            <div class="product-actions-overlay">
                                                <button class="action-btn" onclick="addToWishList({{ $product->id }})" title="Add to Wishlist">
                                                    <i class="far fa-heart"></i>
                                                </button>
                                                <button class="action-btn action-btn-add" onclick="quickAddToCart({{ $product->id }})" title="Quick Add">
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
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= 4 ? '' : 'empty' }}"></i>
                                                    @endfor
                                                </div>
                                                <span class="rating-count">({{ $product->product_ratings_count ?? 0 }})</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12">
                                <div class="text-center py-5">
                                    <i class="fas fa-search" style="font-size: 4rem; color: var(--color-gray-300);"></i>
                                    <h3 class="mt-4">No Products Found</h3>
                                    <p class="text-muted">Try adjusting your filters or search terms</p>
                                    <a href="{{ route('front.shop') }}" class="btn-primary-premium mt-3">Clear Filters</a>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Pagination -->
                    @if($products->hasPages())
                        <div class="mt-5">
                            {{ $products->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

@endsection

@section('customJs')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js"></script>
    <script>
        var rangeSlider;

        $(document).ready(function() {
            try {
                // Price range slider - Initialize properly
                var sliderElement = $(".js-range-slider");
                if (sliderElement.length) {
                    var slider = sliderElement.ionRangeSlider({
                        type: "double",
                        min: 0,
                        max: 10000,
                        from: {{ $priceMin }},
                        to: {{ $priceMax }},
                        step: 100,
                        skin: "round",
                        postfix: " ৳",
                        force_edges: true,
                        onFinish: function(data) {
                            apply_filters(data.from, data.to);
                        }
                    });

                    // Store slider instance
                    rangeSlider = slider.data("ionRangeSlider");
                }
            } catch(e) {
                console.log('Slider initialization error:', e);
            }

            // Brand filter change
            $(".brand-label").change(function() {
                apply_filters();
            });

            // Sort change
            $("#sort").change(function() {
                apply_filters();
            });
        });

        // Apply filters function
        function apply_filters(priceFrom, priceTo) {
            var brands = [];

            $(".brand-label").each(function() {
                if ($(this).is(":checked") == true) {
                    brands.push($(this).val());
                }
            });

            // Get price values from slider
            var from, to;
            try {
                if (typeof rangeSlider !== 'undefined' && rangeSlider && rangeSlider !== null) {
                    from = (priceFrom !== undefined && priceFrom !== null) ? priceFrom : rangeSlider.result.from;
                    to = (priceTo !== undefined && priceTo !== null) ? priceTo : rangeSlider.result.to;
                } else {
                    from = {{ $priceMin }};
                    to = {{ $priceMax }};
                }
            } catch(e) {
                from = {{ $priceMin }};
                to = {{ $priceMax }};
            }

            var url = '{{ url()->current() }}?'

            if (brands.length > 0) {
                url += '&brand=' + brands.toString()
            }

            url += '&price_min=' + from + '&price_max=' + to;

            var keyword = $("#search").val();
            if (keyword && keyword.length > 0) {
                url += '&search=' + keyword;
            }

            url += '&sort=' + $("#sort").val();

            window.location.href = url;
        }
    </script>
@endsection