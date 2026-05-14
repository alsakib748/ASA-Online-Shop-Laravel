<!DOCTYPE html>
<html class="no-js" lang="en_AU">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>@yield('title', 'ASA Online Shop')</title>
    <meta name="description" content="Premium Online Shopping Experience" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <meta property="og:locale" content="en_AU" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="@yield('title', 'ASA Online Shop')" />
    <meta property="og:description" content="Premium Online Shopping Experience" />

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Premium CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/premium.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/custom.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/ion.rangeSlider.min.css') }}" />

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="shortcut icon" type="image/x-icon" href="#" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <style>
        :root {
            --color-primary: #1a1a1a;
            --color-accent: #3b82f6;
            --color-gray-50: #f9fafb;
            --color-gray-100: #f3f4f6;
            --color-gray-200: #e5e7eb;
            --color-gray-300: #d1d5db;
            --color-gray-400: #9ca3af;
            --color-gray-500: #6b7280;
            --color-gray-600: #4b5563;
            --color-gray-700: #374151;
            --color-gray-800: #1f2937;
            --color-gray-900: #111827;
            --color-white: #ffffff;
            --color-success: #10b981;
            --color-warning: #f59e0b;
            --color-danger: #ef4444;
            --color-sale: #dc2626;
            --radius-lg: 10px;
            --radius-xl: 16px;
            --radius-full: 9999px;
            --shadow-card: 0 2px 8px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --space-sm: 0.5rem;
            --space-md: 1rem;
            --space-lg: 1.5rem;
            --space-xl: 2rem;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 15px;
            line-height: 1.6;
            color: var(--color-gray-700);
            background-color: var(--color-gray-50);
        }

        .container {
            max-width: 1200px;
        }
    </style>
</head>

<body data-instant-intensity="mousedown">

    <!-- Top Bar -->
    <div class="bg-dark py-2 d-none d-md-block">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <span class="text-white-50 small">
                        <i class="fas fa-phone me-2"></i>Need help? Call us: +880 1234 567890
                    </span>
                </div>
                <div class="col-md-6 text-end">
                    <div class="d-inline-flex gap-3">
                        <a href="#" class="text-white-50 small text-decoration-none"><i
                                class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white-50 small text-decoration-none"><i
                                class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white-50 small text-decoration-none"><i
                                class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white-50 small text-decoration-none"><i
                                class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="bg-white shadow-sm sticky-top">
        <div class="container">
            <div class="row align-items-center py-3">
                <!-- Logo -->
                <div class="col-6 col-lg-2">
                    <a href="{{ route('front.home') }}" class="text-decoration-none">
                        <span class="h3 fw-bold text-dark">ASA <span class="text-primary">Shop</span></span>
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="col-12 col-lg-6 order-2 order-lg-1 mt-3 mt-lg-0">
                    <form action="{{ route('front.shop') }}" method="GET">
                        <div class="input-group">
                            <input type="text" value="{{ Request::get('search') }}"
                                placeholder="Search for products, brands..." name="search"
                                class="form-control border-secondary"
                                style="border-radius: var(--radius-lg) 0 0 var(--radius-lg);">
                            <button type="submit" class="btn btn-dark px-4"
                                style="border-radius: 0 var(--radius-lg) var(--radius-lg) 0;">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Header Actions -->
                <div class="col-6 col-lg-4 text-end order-1 order-lg-2">
                    <div class="d-flex justify-content-end align-items-center gap-2 gap-lg-3">
                        <!-- Account -->
                        @if (Auth::check())
                            <a href="{{ route('dashboard') }}"
                                class="btn btn-outline-dark btn-sm d-none d-lg-inline-block">
                                <i class="fas fa-user me-1"></i> My Account
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-dark btn-sm d-none d-lg-inline-block">
                                <i class="fas fa-sign-in-alt me-1"></i> Sign In
                            </a>
                        @endif

                        <!-- Wishlist -->
                        <a href="{{ route('front.wishlist') }}" class="btn btn-outline-secondary position-relative p-2">
                            <i class="far fa-heart"></i>
                            @php $wishlistCount = \App\Models\Wishlist::where('user_id', Auth::id())->count() ?? 0 @endphp
                            @if ($wishlistCount > 0)
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                    style="font-size: 10px;">
                                    {{ $wishlistCount }}
                                </span>
                            @endif
                        </a>

                        <!-- Cart -->
                        <a href="{{ route('front.cart') }}" class="btn btn-outline-secondary position-relative p-2">
                            <i class="fas fa-shopping-bag"></i>
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                style="font-size: 10px;">
                                {{ Cart::count() }}
                            </span>
                        </a>

                        <!-- Mobile Menu Toggle -->
                        <button class="btn btn-dark d-lg-none p-2" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#mobileMenu">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Navigation Menu -->
    <nav class="premium-nav bg-dark">
        <div class="container">
            <div class="d-none d-lg-flex align-items-center">
                <!-- Main Menu -->
                <ul class="navbar-nav me-auto mb-0">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('front.home') }}">
                            <i class="fas fa-home me-1"></i> Home
                        </a>
                    </li>

                    @if (getCategories()->isNotEmpty())
                        @foreach (getCategories() as $category)
                            <li class="nav-item has-submenu">
                                <a class="nav-link text-white" href="{{ route('front.shop', $category->slug) }}">
                                    {{ $category->name }}
                                    @if ($category->sub_category->isNotEmpty())
                                        <i class="fas fa-chevron-down ms-1 chevron-icon"></i>
                                    @endif
                                </a>
                                @if ($category->sub_category->isNotEmpty())
                                    <ul class="dropdown-menu dropdown-menu-dark submenu">
                                        @foreach ($category->sub_category as $subCategory)
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('front.shop', [$category->slug, $subCategory->slug]) }}">
                                                    {{ $subCategory->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    @endif

                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('front.shop') }}">Shop</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Mobile Offcanvas Menu -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="mobileMenu">
        <div class="offcanvas-header bg-dark">
            <h5 class="offcanvas-title text-white">Menu</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body bg-dark">
            <!-- Mobile Search -->
            <form action="{{ route('front.shop') }}" method="GET" class="mb-3">
                <div class="input-group">
                    <input type="text" value="{{ Request::get('search') }}" placeholder="Search products..."
                        name="search" class="form-control">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>

            <!-- Mobile Menu Items -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('front.home') }}">
                        <i class="fas fa-home me-2"></i> Home
                    </a>
                </li>

                @if (getCategories()->isNotEmpty())
                    @foreach (getCategories() as $category)
                        <li class="nav-item">
                            <a class="nav-link text-white" data-bs-toggle="collapse"
                                href="#mobileSubmenu{{ $category->id }}" role="button">
                                {{ $category->name }}
                                @if ($category->sub_category->isNotEmpty())
                                    <i class="fas fa-chevron-down float-end"></i>
                                @endif
                            </a>
                            @if ($category->sub_category->isNotEmpty())
                                <div class="collapse" id="mobileSubmenu{{ $category->id }}">
                                    <ul class="list-unstyled ps-3 border-start border-secondary">
                                        @foreach ($category->sub_category as $subCategory)
                                            <li>
                                                <a class="nav-link text-white-50"
                                                    href="{{ route('front.shop', [$category->slug, $subCategory->slug]) }}">
                                                    {{ $subCategory->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </li>
                    @endforeach
                @endif

                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('front.shop') }}">Shop</a>
                </li>

                @if (!Auth::check())
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-2"></i> Sign In
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white pt-5 pb-3">
        <div class="container">
            <div class="row g-4 mb-4">
                <!-- Brand -->
                <div class="col-lg-3 col-md-6">
                    <h4 class="fw-bold mb-3"><span class="text-light">ASA</span> <span
                            class="text-primary">Shop</span></h4>
                    <p class="text-white-50 small mb-3">Your premier destination for quality products. We offer the
                        best selection with fast shipping.</p>
                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-secondary btn-sm"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="btn btn-secondary btn-sm"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="btn btn-secondary btn-sm"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="btn btn-secondary btn-sm"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-bold mb-3 text-light">Shop</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('front.shop') }}" class="text-white-50 text-decoration-none small">All
                                Products</a></li>
                        <li><a href="{{ route('front.shop') }}?sort=latest"
                                class="text-white-50 text-decoration-none small">New Arrivals</a></li>
                        @if (getCategories()->isNotEmpty())
                            @foreach (getCategories()->take(4) as $category)
                                <li><a href="{{ route('front.shop', $category->slug) }}"
                                        class="text-white-50 text-decoration-none small">{{ $category->name }}</a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>

                <!-- Customer Service -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-bold mb-3 text-light">Customer Service</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('front.page', 'contact-us') }}"
                                class="text-white-50 text-decoration-none small">Contact Us</a></li>
                        <li><a href="{{ route('front.page', 'shipping-policy') }}"
                                class="text-white-50 text-decoration-none small">Shipping Info</a></li>
                        <li><a href="{{ route('front.page', 'return-policy') }}"
                                class="text-white-50 text-decoration-none small">Returns & Exchanges</a></li>
                        <li><a href="{{ route('front.page', 'faq') }}"
                                class="text-white-50 text-decoration-none small">FAQ</a></li>
                    </ul>
                </div>

                <!-- My Account -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-bold mb-3 text-light">My Account</h6>
                    <ul class="list-unstyled">
                        @if (Auth::check())
                            <li><a href="{{ route('dashboard') }}"
                                    class="text-white-50 text-decoration-none small">Dashboard</a></li>
                            <li><a href="{{ route('front.orders') }}"
                                    class="text-white-50 text-decoration-none small">My Orders</a></li>
                            <li><a href="{{ route('front.wishlist') }}"
                                    class="text-white-50 text-decoration-none small">Wishlist</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="text-white-50 text-decoration-none small">Sign
                                    In</a></li>
                            <li><a href="{{ route('register') }}"
                                    class="text-white-50 text-decoration-none small">Register</a></li>
                        @endif
                    </ul>
                </div>
            </div>

            <hr class="border-secondary">

            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="small text-white-50 mb-0">&copy; {{ date('Y') }} ASA Online Shop. All rights
                        reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="d-flex justify-content-center justify-content-md-end gap-3">
                        <i class="fab fa-cc-visa fs-4 text-white-50"></i>
                        <i class="fab fa-cc-mastercard fs-4 text-white-50"></i>
                        <i class="fab fa-cc-amex fs-4 text-white-50"></i>
                        <i class="fab fa-cc-paypal fs-4 text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Wishlist Modal -->
    <div class="modal fade" id="wishlistModal" tabindex="-1" aria-labelledby="wishlistModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: var(--radius-xl);">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center pb-5">
                    <div class="mb-3">
                        <i class="fas fa-check-circle fs-1 text-success"></i>
                    </div>
                    <h5 class="mb-2">Success!</h5>
                    <p class="text-muted mb-0" id="wishlistMessage"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script src="{{ asset('front-assets/js/lazyload.17.6.0.min.js') }}"></script>
    <script src="{{ asset('front-assets/js/custom.js') }}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")
            }
        });

        function addToCart(id) {
            $.ajax({
                url: '{{ route('front.addToCart') }}',
                method: 'POST',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == true) {
                        window.location.href = "{{ route('front.cart') }}";
                    } else {
                        alert(response.message);
                    }
                }
            });
        }

        function quickAddToCart(id) {
            $.ajax({
                url: '{{ route('front.addToCart') }}',
                method: 'POST',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == true) {
                        // Update cart badge
                        const cartLink = document.querySelector('a[href="{{ route('front.cart') }}"]');
                        const badge = cartLink.querySelector('.badge');
                        if (badge) {
                            badge.textContent = parseInt(badge.textContent) + 1;
                        }
                        alert('Product added to cart!');
                    } else {
                        alert(response.message);
                    }
                }
            });
        }

        function addToWishList(id) {
            $.ajax({
                url: '{{ route('front.addToWishList') }}',
                method: 'POST',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == true) {
                        $("#wishlistMessage").html(response.message);
                        $("#wishlistModal").modal("show");
                    } else {
                        window.location.href = "{{ route('login') }}";
                    }
                }
            });
        }
    </script>

    @yield('customJs')

</body>

</html>
