@extends('front.layouts.app')

@section('title', 'Checkout - ASA Online Shop')

@section('content')

    <!-- Breadcrumb -->
    <section class="py-4" style="background: var(--color-gray-50);">
        <div class="container">
            <nav class="breadcrumb-premium">
                <a href="{{ route('front.home') }}">Home</a>
                <span>/</span>
                <a href="{{ route('front.shop') }}">Shop</a>
                <span>/</span>
                <a href="{{ route('front.cart') }}">Cart</a>
                <span>/</span>
                <span class="current">Checkout</span>
            </nav>
        </div>
    </section>

    <!-- Checkout Content -->
    <section class="checkout-page">
        <div class="container">
            <div class="row">
                <!-- Form Column -->
                <div class="col-lg-8">
                    <!-- Shipping Address -->
                    <div class="checkout-form-card mb-4">
                        <h3 class="checkout-title">
                            <i class="fas fa-map-marker-alt"></i> Shipping Address
                        </h3>
                        <form action="" method="POST" name="shipping-address" id="shipping-address">
                            @csrf
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">First Name *</label>
                                    <input type="text" name="first_name" id="first_name" class="form-input"
                                        value="{{ !empty($customerAddress) ? $customerAddress->first_name : '' }}">
                                    <p class="text-danger small mt-1" id="first_name_error"></p>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Last Name *</label>
                                    <input type="text" name="last_name" id="last_name" class="form-input"
                                        value="{{ !empty($customerAddress) ? $customerAddress->last_name : '' }}">
                                    <p class="text-danger small mt-1" id="last_name_error"></p>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Email *</label>
                                    <input type="email" name="email" id="email" class="form-input"
                                        value="{{ !empty($customerAddress) ? $customerAddress->email : '' }}">
                                    <p class="text-danger small mt-1" id="email_error"></p>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Mobile *</label>
                                    <input type="text" name="mobile" id="mobile" class="form-input"
                                        value="{{ !empty($customerAddress) ? $customerAddress->mobile : '' }}">
                                    <p class="text-danger small mt-1" id="mobile_error"></p>
                                </div>
                            </div>

                            <div class="form-group full">
                                <label class="form-label">Shipping Area *</label>
                                <select name="country" id="country" class="form-select">
                                    <option value="">Select Shipping Area</option>
                                    @if ($countries->isNotEmpty())
                                        @foreach ($countries as $country)
                                            <option
                                                {{ !empty($customerAddress) && $customerAddress->country_id == $country->id ? 'selected' : '' }}
                                                value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <p class="text-danger small mt-1" id="country_error"></p>
                            </div>

                            <div class="form-group full">
                                <label class="form-label">Address *</label>
                                <textarea name="address" id="address" cols="30" rows="2" class="form-input" placeholder="Street address">{{ !empty($customerAddress) ? $customerAddress->address : '' }}</textarea>
                                <p class="text-danger small mt-1" id="address_error"></p>
                            </div>

                            <div class="form-group full">
                                <label class="form-label">Apartment, suite, etc. (optional)</label>
                                <input type="text" name="apartment" id="apartment" class="form-input"
                                    value="{{ !empty($customerAddress) ? $customerAddress->apartment : '' }}">
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">City *</label>
                                    <input type="text" name="city" id="city" class="form-input"
                                        value="{{ !empty($customerAddress) ? $customerAddress->city : '' }}">
                                    <p class="text-danger small mt-1" id="city_error"></p>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">State *</label>
                                    <input type="text" name="state" id="state" class="form-input"
                                        value="{{ !empty($customerAddress) ? $customerAddress->state : '' }}">
                                    <p class="text-danger small mt-1" id="state_error"></p>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Zip Code *</label>
                                    <input type="text" name="zip" id="zip" class="form-input"
                                        value="{{ !empty($customerAddress) ? $customerAddress->zip : '' }}">
                                    <p class="text-danger small mt-1" id="zip_error"></p>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Order Notes (optional)</label>
                                    <input type="text" name="order_notes" id="order_notes" class="form-input"
                                        placeholder="Special instructions for delivery">
                                </div>
                            </div>

                            <button type="submit" class="btn-primary-premium">
                                Save Address <i class="fas fa-check ms-2"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Payment Method -->
                    <div class="checkout-form-card">
                        <h3 class="checkout-title">
                            <i class="fas fa-credit-card"></i> Payment Method
                        </h3>

                        <div class="payment-methods">
                            <label class="payment-option selected" onclick="selectPayment(this, 'cod')">
                                <input type="radio" name="payment_method" value="cod" checked>
                                <span class="payment-radio"></span>
                                <div class="payment-info">
                                    <h4>Cash on Delivery</h4>
                                    <p>Pay when you receive your order</p>
                                </div>
                            </label>

                            <label class="payment-option" onclick="selectPayment(this, 'stripe')">
                                <input type="radio" name="payment_method" value="stripe">
                                <span class="payment-radio"></span>
                                <div class="payment-info">
                                    <h4>Pay with Stripe</h4>
                                    <p>Secure online payment via Stripe</p>
                                </div>
                            </label>
                        </div>

                        @php
                            $user_id = Auth::user()->id ?? 0;
                            $result = \App\Models\CustomerAddress::where('user_id', $user_id)->count();
                        @endphp

                        @if ($result >= 1)
                            <form action="{{ route('front.processCheckout') }}" method="POST" name="payment-method"
                                id="paymentMethodForm">
                                @csrf
                                <input type="hidden" name="payment_method" value="cod">
                                <button type="submit" class="btn-primary-premium w-100 mt-4">
                                    <i class="fas fa-lock me-2"></i> Place Order
                                </button>
                            </form>
                        @else
                            <div class="alert alert-warning mt-4" style="border-radius: var(--radius-lg);">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Please save your shipping address first before placing an order.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-lg-4">
                    <div class="order-summary">
                        <h3 class="summary-title">Order Summary</h3>

                        @foreach (Cart::content() as $item)
                            <div class="summary-product">
                                <div class="summary-product-image">
                                    @if (!empty($item->options->productImage->image))
                                        <img
                                            src="{{ asset('uploads/product/small/' . $item->options->productImage->image) }}">
                                    @else
                                        <img src="{{ asset('admin-assets/img/default-150x150.png') }}">
                                    @endif
                                </div>
                                <div class="summary-product-info">
                                    <h4>{{ $item->name }}</h4>
                                    <p>Qty: {{ $item->qty }}</p>
                                </div>
                                <div class="summary-product-price">৳{{ number_format($item->price * $item->qty) }}</div>
                            </div>
                        @endforeach

                        <hr style="border-color: var(--color-gray-200);">

                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span>৳{{ Cart::subtotal() }}</span>
                        </div>

                        <div class="summary-row">
                            <span>Discount</span>
                            <span class="text-success">-৳{{ $discount }}</span>
                        </div>

                        <div class="summary-row">
                            <span>Shipping</span>
                            <span id="shippingAmount">৳{{ number_format($totalShippingCharge, 2) }}</span>
                        </div>

                        <div class="summary-row total">
                            <span>Total</span>
                            <span id="grandTotal">৳{{ number_format($grandTotal, 2) }}</span>
                        </div>

                        <!-- Coupon -->
                        <div class="coupon-form">
                            <input type="text" id="discount_code" name="discount_code" placeholder="Coupon code">
                            <button type="button" id="apply-discount">Apply</button>
                        </div>

                        <div id="discount-response-wrapper">
                            @if (Session::has('code'))
                                <div class="mt-3 p-2"
                                    style="background: var(--color-gray-50); border-radius: var(--radius-md);"
                                    id="discount-response">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold">{{ Session::get('code')->code }}</span>
                                        <a href="" id="remove-discount" class="text-danger">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('customJs')
    <script>
        // Payment selection
        function selectPayment(element, value) {
            document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('selected'));
            element.classList.add('selected');
            document.querySelector('input[name="payment_method"]').value = value;
            document.querySelector('#paymentMethodForm input[name="payment_method"]').value = value;
        }

        $(document).ready(function() {
            // Apply discount
            $("#apply-discount").click(function() {
                $.ajax({
                    url: "{{ route('front.applyDiscount') }}",
                    type: 'post',
                    data: {
                        code: $("#discount_code").val(),
                        country_id: $("#country").val()
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == true) {
                            $("#shippingAmount").html('৳' + response.shippingCharge);
                            $("#grandTotal").html('৳' + response.grandTotal);
                            $("#discount-response-wrapper").html(response.discountString);
                        } else {
                            $("#discount-response-wrapper").html("<span class='text-danger'>" +
                                response.message + "</span>");
                        }
                    }
                });
            });

            // Country change - update order summary
            $("#country").change(function() {
                $.ajax({
                    url: '{{ route('front.getOrderSummery') }}',
                    type: 'post',
                    data: {
                        country_id: $(this).val()
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == true) {
                            $("#shippingAmount").html('৳' + response.shippingCharge);
                            $("#grandTotal").html('৳' + response.grandTotal);
                        }
                    }
                });
            });

            // Shipping address form
            $("#shipping-address").submit(function(event) {
                event.preventDefault();
                $('button[type="submit"]').prop("disabled", true);

                $.ajax({
                    url: "{{ route('front.shippingAddress') }}",
                    type: 'POST',
                    data: $(this).serializeArray(),
                    dataType: 'json',
                    success: function(response) {
                        $('button[type="submit"]').prop("disabled", false);

                        // Clear previous errors
                        $('.text-danger').html('');
                        $('.form-input').removeClass('is-invalid');

                        if (response.status == false) {
                            var errors = response.errors;

                            if (errors.first_name) {
                                $("#first_name").addClass("is-invalid");
                                $("#first_name_error").html(errors.first_name);
                            }
                            if (errors.last_name) {
                                $("#last_name").addClass("is-invalid");
                                $("#last_name_error").html(errors.last_name);
                            }
                            if (errors.email) {
                                $("#email").addClass("is-invalid");
                                $("#email_error").html(errors.email);
                            }
                            if (errors.country) {
                                $("#country").addClass("is-invalid");
                                $("#country_error").html(errors.country);
                            }
                            if (errors.address) {
                                $("#address").addClass("is-invalid");
                                $("#address_error").html(errors.address);
                            }
                            if (errors.state) {
                                $("#state").addClass("is-invalid");
                                $("#state_error").html(errors.state);
                            }
                            if (errors.city) {
                                $("#city").addClass("is-invalid");
                                $("#city_error").html(errors.city);
                            }
                            if (errors.zip) {
                                $("#zip").addClass("is-invalid");
                                $("#zip_error").html(errors.zip);
                            }
                            if (errors.mobile) {
                                $("#mobile").addClass("is-invalid");
                                $("#mobile_error").html(errors.mobile);
                            }
                        } else {
                            window.location.href = "{{ route('front.checkout') }}";
                        }
                    }
                });
            });

            // Remove discount
            $('body').on('click', '#remove-discount', function() {
                $.ajax({
                    url: "{{ route('front.removeCoupon') }}",
                    type: 'post',
                    data: {
                        country_id: $("#country").val()
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == true) {
                            $("#shippingAmount").html('৳' + response.shippingCharge);
                            $("#grandTotal").html('৳' + response.grandTotal);
                            $("#discount-response-wrapper").html('');
                            $("#discount_code").val('');
                        }
                    }
                });
                return false;
            });
        });
    </script>
@endsection
