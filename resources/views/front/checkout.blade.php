@extends('front.layouts.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                    <li class="breadcrumb-item"><a class="white-text" href="#">Shop</a></li>
                    <li class="breadcrumb-item">Checkout</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-9 pt-4">
        <div class="container">

            <div class="row">
                <div class="col-md-7">
                    <div class="sub-title">
                        <h2>Shipping Address</h2>
                    </div>
                    <div class="card shadow-lg border-0">
                        <div class="card-body checkout-form">
                            <form action="" method="POST" name="shipping-address" id="shipping-address">

                                @csrf
                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="first_name" id="first_name" class="form-control"
                                                placeholder="First Name"
                                                value="{{ !empty($customerAddress) ? $customerAddress->first_name : '' }}">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="last_name" id="last_name" class="form-control"
                                                placeholder="Last Name"
                                                value="{{ !empty($customerAddress) ? $customerAddress->last_name : '' }}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="email" id="email" class="form-control"
                                                placeholder="Email"
                                                value="{{ !empty($customerAddress) ? $customerAddress->email : '' }}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <select name="country" id="country" class="form-control">
                                                <option value="">Select a Country</option>
                                                @if ($countries->isNotEmpty())
                                                    @foreach ($countries as $country)
                                                        <option
                                                            {{ !empty($customerAddress) && $customerAddress->country_id == $country->id ? 'selected' : '' }}
                                                            value="{{ $country->id }}">{{ $country->name }}</option>
                                                    @endforeach
                                                    <option value="rest_of_world">Rest of World</option>
                                                @endif
                                            </select>
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <textarea name="address" id="address" cols="30" rows="3" placeholder="Address" class="form-control">{{ !empty($customerAddress) ? $customerAddress->address : '' }}</textarea>
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="apartment" id="apartment" class="form-control"
                                                placeholder="Apartment, suite, unit, etc. (optional)"
                                                value="{{ !empty($customerAddress) ? $customerAddress->apartment : '' }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" name="city" id="city" class="form-control"
                                                placeholder="City"
                                                value="{{ !empty($customerAddress) ? $customerAddress->city : '' }}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" name="state" id="state" class="form-control"
                                                placeholder="State"
                                                value="{{ !empty($customerAddress) ? $customerAddress->state : '' }}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" name="zip" id="zip" class="form-control"
                                                placeholder="Zip"
                                                value="{{ !empty($customerAddress) ? $customerAddress->zip : '' }}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="mobile" id="mobile" class="form-control"
                                                placeholder="Mobile No."
                                                value="{{ !empty($customerAddress) ? $customerAddress->mobile : '' }}">
                                            <p></p>
                                        </div>
                                    </div>


                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <textarea name="order_notes" id="order_notes" cols="30" rows="2" placeholder="Order Notes (optional)"
                                                class="form-control"></textarea>
                                        </div>
                                    </div>

                                    <div class="pt-4">
                                        <button type="submit" class="btn-dark btn btn-block w-100">Submit Shipping
                                            Address</button>
                                    </div>

                                </div>
                            </form>

                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="sub-title">
                        <h2>Order Summery</h3>
                    </div>
                    <div class="card cart-summery">
                        <div class="card-body">
                            @foreach (Cart::content() as $item)
                                <div class="d-flex justify-content-between pb-2">
                                    <div class="h6">{{ $item->name }} X {{ $item->qty }}</div>
                                    <div class="h6">${{ $item->price * $item->qty }}</div>
                                </div>
                            @endforeach

                            <div class="d-flex justify-content-between summery-end">
                                <div class="h6"><strong>Subtotal</strong></div>
                                <div class="h6"><strong>${{ Cart::subtotal() }}</strong></div>
                            </div>
                            <div class="d-flex justify-content-between summery-end">
                                <div class="h6"><strong>Discount</strong></div>
                                <div class="h6"><strong id="discount_value">${{ $discount }}</strong></div>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <div class="h6"><strong>Shipping</strong></div>
                                <div class="h6"><strong
                                        id="shippingAmount">${{ number_format($totalShippingCharge, 2) }}</strong>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-2 summery-end">
                                <div class="h5"><strong>Total</strong></div>
                                <div class="h5"><strong id="grandTotal">${{ number_format($grandTotal, 2) }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="input-group apply-coupan mt-4">
                        <input type="text" id="discount_code" name="discount_code" class="form-control"
                            placeholder="Coupon Code" />
                        <button class="btn btn-dark" type="button" name="apply-discount" id="apply-discount">Apply
                            Coupon</button>
                    </div>

                    <div id="discount-response-wrapper">
                        @if (Session::has('code'))
                            <div class="mt-4" id="discount-response">
                                <strong>{{ Session::get('code')->code }}</strong>
                                <a href="" id="remove-discount" class="btn btn-sm btn-danger"><i
                                        class="fa fa-times"></i></a>
                            </div>
                        @endif
                    </div>

                    <div class="card payment-form ">
                        <h3 class="card-title h5 mb-3 text-center">Payment Method</h3>

                        {{-- <div class="">
                                <button type="submit" class="btn btn-dark btn-block w-100">Cash On Delivery</button> <br/><br/>
                                <button type="submit" class="btn btn-dark btn-block w-100"><a href="#" class="text-light">Pay With Stripe</a></button>
                            </div> --}}

                        <div class="card-body">
                            <form action="{{ route('front.processCheckout') }}" method="POST" name="payment-method" id="payment-method">
                                @csrf
                                <div class="d-md-flex align-items-md-center justify-content-md-evenly">
                                    <div class="">
                                        <input checked type="radio" name="payment_method" class=""
                                            value="cod" id="payment_method_one">
                                        <label for="payment_method_one" class="form-check-label">COD</label>
                                    </div>

                                    <div class="">
                                        <input type="radio" name="payment_method" class="" value="stripe"
                                            id="payment_method_two">
                                        <label for="payment_method_two" class="form-check-label">Stripe</label>
                                    </div>
                                </div>

                                <div class="pt-4">

                                    {{-- <button type="submit" class="btn-dark btn btn-block w-100">Pay Now</button> --}}
                                    @php
                                        $user_id = Auth::user()->id;
                                        $result = App\Models\CustomerAddress::where('user_id', $user_id)->count();
                                    @endphp

                                    @if ($result == 1)
                                        <button type="submit" class="btn-dark btn btn-block w-100">Pay Now</button>
                                    @else
                                        <button type="button" disabled class="btn-dark btn btn-block w-100">Pay
                                            Now</button>
                                    @endif


                                </div>

                            </form>
                        </div>

                    </div>

                    <!-- CREDIT CARD FORM ENDS HERE -->

                </div>
            </div>

        </div>
    </section>
@endsection

@section('customJs')
    <script>
        $("#payment_method_one").click(function() {
            if ($(this).is(':checked') == true) {
                $("#card-payment-form").addClass("d-none");
            }
        });

        $("#payment_method_two").click(function() {
            if ($(this).is(':checked') == true) {
                $("#card-payment-form").removeClass("d-none");
            }
        });

        // $.ajaxSetup({
        //     headers: {
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //     }
        // });

        $(document).ready(function() {

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
                            $("#shippingAmount").html('$' + response.shippingCharge);
                            $("#grandTotal").html('$' + response.grandTotal);
                            $("#discount_value").html('$' + response.discount);
                            $("#discount-response-wrapper").html(response.discountString);
                        } else {
                            $("#discount-response-wrapper").html("<span class='text-danger'>" +
                                response
                                .message + "</span>");
                        }
                    }
                });

            });

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
                            $("#shippingAmount").html('$' + response.shippingCharge);
                            $("#grandTotal").html('$' + response.grandTotal);
                        }
                    }
                });
            });

            $("#shipping-address").submit(function(event) {

                event.preventDefault();

                $('button[type="submit"]').prop("disabled", true);

                $.ajax({
                    url: "{{ route('front.shippingAddress') }}",
                    type: 'POST',
                    data: $(this).serializeArray(),
                    dataType: 'json',
                    success: function(response) {
                        var errors = response.errors;

                        $('button[type="submit"]').prop("disabled", false);

                        if (response.status == false) {
                            if (errors.first_name) {
                                $("#first_name").addClass("is-invalid").siblings("p")
                                    .addClass(
                                        "invalid-feedback").html(errors.first_name);
                            } else {
                                $("#first_name").removeClass("is-invalid").siblings("p")
                                    .removeClass(
                                        "invalid-feedback").html('');
                            }

                            if (errors.last_name) {
                                $("#last_name").addClass("is-invalid").siblings("p")
                                    .addClass(
                                        "invalid-feedback").html(errors.last_name);
                            } else {
                                $("#last_name").removeClass("is-invalid").siblings("p")
                                    .removeClass(
                                        "invalid-feedback").html('');
                            }

                            if (errors.email) {
                                $("#email").addClass("is-invalid").siblings("p").addClass(
                                    "invalid-feedback").html(errors.email);
                            } else {
                                $("#email").removeClass("is-invalid").siblings("p")
                                    .removeClass(
                                        "invalid-feedback").html('');
                            }

                            if (errors.country) {
                                $("#country").addClass("is-invalid").siblings("p").addClass(
                                    "invalid-feedback").html(errors.country);
                            } else {
                                $("#country").removeClass("is-invalid").siblings("p")
                                    .removeClass(
                                        "invalid-feedback").html('');
                            }

                            if (errors.address) {
                                $("#address").addClass("is-invalid").siblings("p").addClass(
                                    "invalid-feedback").html(errors.address);
                            } else {
                                $("#address").removeClass("is-invalid").siblings("p")
                                    .removeClass(
                                        "invalid-feedback").html('');
                            }

                            if (errors.state) {
                                $("#state").addClass("is-invalid").siblings("p").addClass(
                                    "invalid-feedback").html(errors.state);
                            } else {
                                $("#state").removeClass("is-invalid").siblings("p")
                                    .removeClass(
                                        "invalid-feedback").html('');
                            }

                            if (errors.city) {
                                $("#city").addClass("is-invalid").siblings("p").addClass(
                                        "invalid-feedback")
                                    .html(errors.city);
                            } else {
                                $("#city").removeClass("is-invalid").siblings("p")
                                    .removeClass(
                                        "invalid-feedback").html('');
                            }

                            if (errors.zip) {
                                $("#zip").addClass("is-invalid").siblings("p").addClass(
                                        "invalid-feedback")
                                    .html(errors.zip);
                            } else {
                                $("#zip").removeClass("is-invalid").siblings("p")
                                    .removeClass(
                                        "invalid-feedback").html('');
                            }

                            if (errors.mobile) {
                                $("#mobile").addClass("is-invalid").siblings("p").addClass(
                                    "invalid-feedback").html(errors.mobile);
                            } else {
                                $("#mobile").removeClass("is-invalid").siblings("p")
                                    .removeClass(
                                        "invalid-feedback").html('');
                            }

                        } else {
                            window.location.href = "{{ route('front.checkout') }}";
                        }

                    }
                });
            });

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
                            $("#shippingAmount").html('$' + response.shippingCharge);
                            $("#grandTotal").html('$' + response.grandTotal);
                            $("#discount_value").html('$' + response.discount);
                            $("#discount-response").html(' ');
                            $("#discount_code").val('');
                        }
                    }
                });
            });

        });
    </script>
@endsection
