<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Country;
use App\Models\Payment;
use App\Models\Product;
use Stripe\StripeClient;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Models\DiscountCoupon;
use App\Models\ShippingCharge;
use App\Models\CustomerAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    //
    public function addToCart(Request $request)
    {
        $product = Product::with("product_images")->find($request->id);

        if ($product == null) {
            return response()->json([
                'status' => false,
                'message' => 'Product Not Found',
            ]);
        }

        if (Cart::count() > 0) {
            // echo "Product already in cart";
            // todo; products found in cart

            // todo; Check if this product already in the cart

            // todo; Return as message that product already added in your cart

            // todo; if product not found in the cart, then add product in cart

            $cartContent = Cart::content();

            $productAlreadyExist = false;

            foreach ($cartContent as $item) {
                if ($item->id == $product->id) {
                    $productAlreadyExist = true;
                }
            }

            if ($productAlreadyExist == false) {
                Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : ""]);

                $status = true;
                $message = "<strong>" . $product->title . "</strong> added in your cart successfully";

                session()->flash('success', $message);
            } else {
                $status = false;
                $message = $product->title . " already added in cart";

                session()->flash('error', $message);
            }
        } else {
            // Cart is empty
            Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : ""]);

            $status = true;
            $message = "<strong>" . $product->title . "</strong> added in your cart successfully";

            session()->flash('success', $message);
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
        ]);
    }

    public function cart()
    {
        $cartContent = Cart::content();
        // dd($cartContent);
        return view('front.cart', compact('cartContent'));
    }

    public function updateCart(Request $request)
    {
        $rowId = $request->rowId;
        $qty = $request->qty;

        $itemInfo = Cart::get($rowId);

        $product = Product::find($itemInfo->id);

        // todo; check qty available in stock

        if ($product->track_qty == 'Yes') {

            if ($qty <= $product->qty) {
                Cart::update($rowId, $qty);
                $message = "Cart updated successfully";
                $status = true;
                session()->flash('success', $message);
            } else {
                $message = "Requested quantity(" . $qty . ")is not available in stock";
                $status = false;
                session()->flash('error', $message);
            }
        } else {
            Cart::update($rowId, $qty);
            $message = "Cart updated successfully";
            $status = true;
            session()->flash('success', $message);
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
        ]);
    }

    public function deleteItem(Request $request)
    {

        $itemInfo = Cart::get($request->rowId);

        if ($itemInfo == null) {
            $errorMessage = "Item not found in cart";
            session()->flash("error", $errorMessage);
            return response()->json([
                'status' => false,
                'message' => $errorMessage,
            ]);
        }

        Cart::remove($request->rowId);

        $successMessage = "Item removed from cart successfully";
        session()->flash("success", $successMessage);

        return response()->json([
            'status' => true,
            'message' => $successMessage,
        ]);

    }

    public function checkout()
    {

        if (!Auth::user()) {
            return redirect()->route('login');
        }

        $discount = 0;

        if (Cart::count() == 0) {
            return redirect()->route('front.cart');
        }

        $customerAddress = CustomerAddress::where('user_id', Auth::user()->id)->first();

        // if(!session()->has('url.intended')){
        //     session(['url.intended' => url()->current()]);
        // }

        $countries = Country::orderBy('name', 'ASC')->get();

        $subTotal = Cart::subtotal(2, '.', '');

        // Apply discount here
        if (session()->has('code')) {

            $code = session()->get('code');

            if ($code->type == 'percent') {
                $discount = ($code->discount_amount / 100) * $subTotal;
            } else {
                $discount = $code->discount_amount;
            }
        }

        // calculate shipping here

        if ($customerAddress != null) {
            $userCountry = $customerAddress->country_id;

            $shippingInfo = ShippingCharge::where('country_id', $userCountry)->first();

            $totalQty = 0;
            $totalShippingCharge = 0;
            $grandTotal = 0;
            foreach (Cart::content() as $item) {
                $totalQty += $item->qty;
            }

            $totalShippingCharge = $totalQty * $shippingInfo->amount;

            $grandTotal = ($subTotal - $discount) + $totalShippingCharge;
        } else {
            $grandTotal = ($subTotal - $discount);
            $totalShippingCharge = 0;
        }

        return view('front.checkout', compact(['countries', 'customerAddress', 'totalShippingCharge', 'discount', 'grandTotal']));
    }

    public function shippingAddress(Request $request)
    {
        //todo: Step - 1 Apply Validation

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:5',
            'last_name' => 'required',
            'email' => 'required|email',
            'country' => 'required',
            'address' => 'required|min:30',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'mobile' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Please fix the errors',
                'status' => false,
                'errors' => $validator->errors(),
            ]);

        }

        //todo; step - 2 save user address
        $user = Auth::user();

        CustomerAddress::updateOrCreate(
            ['user_id' => $user->id],
            [
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'country_id' => $request->country,
                'address' => $request->address,
                'apartment' => $request->apartment,
                'city' => $request->city,
                'state' => $request->state,
                'zip' => $request->zip,
            ]
        );

        // $notification = response()->json([
        //     'message' => 'Shipping address saved successfully',
        //     'status' => true,
        // ]);

        // return redirect()->route('front.checkout')->with('notification');
        return response()->json([
            'message' => 'Shipping address saved successfully',
            'status' => true,
        ]);

    }

    public function processCheckout(Request $request)
    {
        //todo; step - 2 save user address
        $user = Auth::user();

        //todo; step - 3 store data in orders table

        if ($request->payment_method == 'cod') {

            $discountCodeId = null;
            $shipping = 0;
            $discount = 0;
            $promoCode = '';
            $subTotal = Cart::subtotal(2, '.', '');

            // Apply discount here
            if (session()->has('code')) {
                $code = session()->get('code');

                if ($code->type == 'percent') {
                    $discount = ($code->discount_amount / 100) * $subTotal;
                } else {
                    $discount = $code->discount_amount;
                }

                $discountCodeId = $code->id;
                $promoCode = $code->code;
            }

            $customerAddress = CustomerAddress::where('user_id', $user->id)->first();

            // calculate shipping
            $shippingInfo = ShippingCharge::where('country_id', $customerAddress->country_id)->first();

            $totalQty = 0;
            foreach (Cart::content() as $item) {
                $totalQty += $item->qty;
            }

            if ($shippingInfo != null) {

                $shipping = $totalQty * $shippingInfo->amount;

                $grandTotal = ($subTotal - $discount) + $shipping;

            } else {
                $shippingInfo = ShippingCharge::where('country_id', 'rest_of_world')->first();

                $shipping = $totalQty * $shippingInfo->amount;

                $grandTotal = ($subTotal - $discount) + $shipping;
            }

            $order = new Order();
            $order->subtotal = Cart::subtotal();
            $order->subtotal = $subTotal;
            $order->shipping = $shipping;
            $order->grand_total = $grandTotal;
            $order->discount = $discount;
            $order->coupon_code_id = $discountCodeId;
            $order->coupon_code = $promoCode;
            $order->payment_status = 'not paid';
            $order->status = 'pending';
            $order->user_id = $user->id;
            $order->first_name = $customerAddress->first_name;
            $order->last_name = $customerAddress->last_name;
            $order->email = $customerAddress->email;
            $order->mobile = $customerAddress->mobile;
            $order->address = $customerAddress->address;
            $order->apartment = $customerAddress->apartment;
            $order->state = $customerAddress->state;
            $order->city = $customerAddress->city;
            $order->zip = $customerAddress->zip;
            $order->notes = $customerAddress->order_notes;
            $order->country_id = $customerAddress->country_id;
            $order->save();

            //todo; step - 4 store order items in order items table

            foreach (Cart::content() as $item) {
                $orderItem = new OrderItem();
                $orderItem->product_id = $item->id;
                $orderItem->order_id = $order->id;
                $orderItem->name = $item->name;
                $orderItem->qty = $item->qty;
                $orderItem->price = $item->price;
                $orderItem->total = $item->price * $item->qty;
                $orderItem->save();

                // Update Product Stock

                $productData = Product::find($item->id);
                if ($productData->track_qty == 'Yes') {
                    $currentQty = $productData->qty;

                    $updatedQty = $currentQty - $item->qty;

                    $productData->qty = $updatedQty;

                    $productData->save();
                }

            }

            // Send Order Email
            orderEmail($order->id, 'customer');

            session()->flash("success", "You have successfully placed your order.");

            Cart::destroy();

            session()->forget('code');

            $notification =  response()->json([
                'message' => 'Order saved successfully',
                'orderId' => $order->id,
                'status' => true,
            ]);

            return redirect()->to('/thanks/' . $order->id)->with('notification', $notification);

        } else if ($request->payment_method == 'stripe') {

            $discountCodeId = null;
            $shipping = 0;
            $discount = 0;
            $promoCode = '';
            $subTotal = Cart::subtotal(2, '.', '');

            //todo: Apply discount here
            if (session()->has('code')) {
                $code = session()->get('code');

                if ($code->type == 'percent') {
                    $discount = ($code->discount_amount / 100) * $subTotal;
                } else {
                    $discount = $code->discount_amount;
                }

                $discountCodeId = $code->id;
                $promoCode = $code->code;
            }

            $customerAddress = CustomerAddress::where('user_id',$user->id)->first();

            //todo: calculate shipping
            $shippingInfo = ShippingCharge::where('country_id', $customerAddress->country_id)->first();

            $totalQty = 0;
            $productNames = [];
            foreach (Cart::content() as $item) {
                $totalQty += $item->qty;
                $productNames[] = $item->name . ' X ' . $item->qty;
            }

            if ($shippingInfo != null) {

                $shipping = $totalQty * $shippingInfo->amount;

                $grandTotal = ($subTotal - $discount) + $shipping;

            } else {
                $shippingInfo = ShippingCharge::where('country_id', 'rest_of_world')->first();

                $shipping = $totalQty * $shippingInfo->amount;

                $grandTotal = ($subTotal - $discount) + $shipping;
            }

            $unitAmount = round($grandTotal * 100);

            // todo: stripe payment gateway integration
            $stripe = new StripeClient(config('stripe.stripe_sk'));

            $response = $stripe->checkout->sessions->create([
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => [
                                'name' => "ASA Online Shop",
                            ],
                            'unit_amount' => $unitAmount,
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',
                'success_url' => route('success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('cancel'),
                'metadata' => [
                    'subTotal' => $subTotal,
                    'discount' => $discount,
                    'shipping' => $shipping,
                    'grandTotal' => $grandTotal,
                    'totalQty' => $totalQty,
                    'country' => $customerAddress->country_id,
                    'promoCode' => $promoCode ?? null,
                    'first_name' => $customerAddress->first_name,
                    'last_name' => $customerAddress->last_name,
                    'email' => $customerAddress->email,
                    'mobile' => $customerAddress->mobile,
                    'address' => $customerAddress->address,
                    'apartment' => $customerAddress->apartment,
                    'state' => $customerAddress->state,
                    'city' => $customerAddress->city,
                    'zip' => $customerAddress->zip,
                    'order_notes' => $customerAddress->order_notes,
                    'product_names' => json_encode($productNames),
                ],
            ]);

            if (isset($response->id) && $response->id != '') {

                session()->put('product_name', $productNames);
                session()->put('quantity', $totalQty);
                session()->put('price', $grandTotal);

                // session()->put('product_name', "T-Shirt");
                // session()->put('quantity', 3);
                // session()->put('price', 200);

                return redirect($response->url);
            } else {
                return redirect()->route('cancel');
            }

        }else{
            return redirect()->route('cancel');
        }

    }

    public function success(Request $request)
    {

        if (isset($request->session_id)) {
            $stripe = new StripeClient(config('stripe.stripe_sk'));

            $response = $stripe->checkout->sessions->retrieve($request->session_id);

            // dd($response);

            $subTotal = $response->metadata->subTotal;
            $discount = $response->metadata->discount;
            $shipping = $response->metadata->shipping;
            $grandTotal = $response->metadata->grandTotal;
            $totalQty = $response->metadata->totalQty;
            $country = $response->metadata->country;
            $promoCode = $response->metadata->promoCode;
            $first_name = $response->metadata->first_name;
            $last_name = $response->metadata->last_name;
            $email = $response->metadata->email;
            $mobile = $response->metadata->mobile;
            $address = $response->metadata->address;
            $apartment = $response->metadata->apartment;
            $state = $response->metadata->state;
            $city = $response->metadata->city;
            $zip = $response->metadata->zip;
            $order_notes = $response->metadata->order_notes;

            //todo; step - 2 save user address
            $user = Auth::user();

            $discountCodeId = null;
            $shipping = 0;
            $discount = 0;
            $promoCode = '';
            $subTotal = Cart::subtotal(2, '.', '');

            // Apply discount here
            if (session()->has('code')) {
                $code = session()->get('code');

                if ($code->type == 'percent') {
                    $discount = ($code->discount_amount / 100) * $subTotal;
                } else {
                    $discount = $code->discount_amount;
                }

                $discountCodeId = $code->id;
                $promoCode = $code->code;
            }

            // calculate shipping
            $shippingInfo = ShippingCharge::where('country_id', $country)->first();

            $totalQty = 0;
            foreach (Cart::content() as $item) {
                $totalQty += $item->qty;
            }

            if ($shippingInfo != null) {

                $shipping = $totalQty * $shippingInfo->amount;

                $grandTotal = ($subTotal - $discount) + $shipping;

            } else {
                $shippingInfo = ShippingCharge::where('country_id', 'rest_of_world')->first();

                $shipping = $totalQty * $shippingInfo->amount;

                $grandTotal = ($subTotal - $discount) + $shipping;
            }

            // todo: Store Order Details
            $order = new Order();
            $order->subtotal = Cart::subtotal();
            $order->subtotal = $subTotal;
            $order->shipping = $shipping;
            $order->grand_total = $grandTotal;
            $order->discount = $discount;
            $order->coupon_code_id = $discountCodeId;
            $order->coupon_code = $promoCode;
            $order->payment_status = 'paid';
            $order->status = 'pending';
            $order->user_id = $user->id;
            $order->first_name = $first_name;
            $order->last_name = $last_name;
            $order->email = $email;
            $order->mobile = $mobile;
            $order->address = $address;
            $order->apartment = $apartment;
            $order->state = $state;
            $order->city = $city;
            $order->zip = $zip;
            $order->notes = $order_notes;
            $order->country_id = $country;
            $order->save();

            //todo: step - 4 store order items in order items table

            foreach (Cart::content() as $item) {
                $orderItem = new OrderItem();
                $orderItem->product_id = $item->id;
                $orderItem->order_id = $order->id;
                $orderItem->name = $item->name;
                $orderItem->qty = $item->qty;
                $orderItem->price = $item->price;
                $orderItem->total = $item->price * $item->qty;
                $orderItem->save();

                //todo: Update Product Stock
                $productData = Product::find($item->id);
                if ($productData->track_qty == 'Yes') {
                    $currentQty = $productData->qty;

                    $updatedQty = $currentQty - $item->qty;

                    $productData->qty = $updatedQty;

                    $productData->save();
                }

            }

            //todo: Retrieve product names from session
            $productNames = session()->get('product_name', []);

            if (!is_array($productNames)) {
                $productNames = json_decode($productNames, true);
            }

            $payment = new Payment();
            $payment->payment_id = $response->id;
            $payment->user_id = $user->id;
            $payment->order_id = $order->id;
            $payment->product_name = implode(', ', $productNames);
            $payment->total_quantity = session()->get('quantity');
            $payment->amount = session()->get('price');
            $payment->currency = $response->currency;
            $payment->payer_name = $response->customer_details->name;
            $payment->payer_email = $response->customer_details->email;
            $payment->payment_status = $response->status;
            $payment->payment_method = "stripe";
            $payment->save();

            // Send Order Email
            orderEmail($order->id, 'customer');

            session()->flash("success", "You have successfully placed your order.");

            Cart::destroy();

            // discount code session remove
            session()->forget('code');
            // stripe session remove
            session()->forget('product_name');
            session()->forget('quantity');
            session()->forget('price');

            $notification =  response()->json([
                'message' => 'Order saved successfully',
                'orderId' => $order->id,
                'status' => true,
            ]);

            return redirect()->to('/thanks/' . $order->id)->with('notification', $notification);

        }else{
            return redirect()->route('cancel');
        }

    }

    public function cancel()
    {
        return "Cancel";
    }

    public function thankYou($id)
    {
        return view('front.thanks', compact('id'));
    }

    public function getOrderSummery(Request $request)
    {
        $subTotal = Cart::subtotal(2, '.', '');
        $discount = 0;
        $discountString = '';

        // Apply discount here
        if (session()->has('code')) {
            $code = session()->get('code');

            if ($code->type == 'percent') {
                $discount = ($code->discount_amount / 100) * $subTotal;
            } else {
                $discount = $code->discount_amount;
            }

            $discountString = '<div class="mt-4" id="discount-response">
            <strong>' . session()->get('code')->code . '</strong>
                <a href="" id="remove-discount" class="btn btn-sm btn-danger"><i class="fa fa-times"></i></a>
            </div>';
        }

        if ($request->country_id > 0) {

            $shippingInfo = ShippingCharge::where('country_id', $request->country_id)->first();

            $totalQty = 0;
            foreach (Cart::content() as $item) {
                $totalQty += $item->qty;
            }

            if ($shippingInfo != null) {

                $shippingCharge = $totalQty * $shippingInfo->amount;

                $grandTotal = ($subTotal - $discount) + $shippingCharge;

                return response()->json([
                    'status' => true,
                    'grandTotal' => number_format($grandTotal, 2),
                    'discount' => number_format($discount, 2),
                    'discountString' => $discountString,
                    'shippingCharge' => number_format($shippingCharge, 2),
                ]);
            } else {
                $shippingInfo = ShippingCharge::where('country_id', 'rest_of_world')->first();

                // dd($shippingInfo);

                $shippingCharge = $totalQty * $shippingInfo->amount;

                $grandTotal = ($subTotal - $discount) + $shippingCharge;

                return response()->json([
                    'status' => true,
                    'grandTotal' => number_format($grandTotal, 2),
                    'discount' => number_format($discount, 2),
                    'discountString' => $discountString,
                    'shippingCharge' => number_format($shippingCharge, 2),
                ]);

            }
        } else {

            return response()->json([
                // $grandTotal = ($subTotal - $discount);
                'status' => true,
                'grandTotal' => number_format(($subTotal - $discount), 2),
                'discount' => number_format($discount, 2),
                'discountString' => $discountString,
                'shippingCharge' => number_format(0, 2),
            ]);

        }
    }
    public function applyDiscount(Request $request)
    {

        $code = DiscountCoupon::where('code', $request->code)->first();

        if ($code == null) {
            return response()->json([
                'status' => false,
                'message' => 'Discount coupon not be empty!',
            ]);
        }

        // check if coupon start date is valid or not

        $now = Carbon::now();

        // echo $now->format('Y-m-d H:i:s');

        if ($code->starts_at != null) {
            $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $code->starts_at);

            if ($now->lt($startDate)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Discount coupon code start ' . $code->starts_at . '. So try later !',
                ]);
            }
        }

        if ($code->expires_at != null) {
            $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $code->expires_at);

            if ($now->gt($endDate)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Discount coupon not  be available yet!',
                ]);
            }
        }

        // Max Uses Check
        if ($code->max_uses > 0) {
            $couponUsed = Order::where('coupon_code_id', $code->id)->count();

            if ($couponUsed >= $code->max_uses) {
                return response()->json([
                    'status' => false,
                    'message' => 'This  coupon code has been used ' . $couponUsed . ' times. Please try another one!',
                ]);
            }
        }

        // Max Uses User Check
        if ($code->max_uses_user > 0) {
            $couponUsedByUser = Order::where(['coupon_code_id' => $code->id, 'user_id' => Auth::user()->id])->count();

            if ($couponUsedByUser >= $code->max_uses_user) {
                return response()->json([
                    'status' => false,
                    'message' => 'You already used this coupon.',
                ]);
            }
        }

        $subTotal = Cart::subtotal(2, '.', '');

        // Min amount condition check
        if ($code->min_amount > 0) {
            if ($subTotal < $code->min_amount) {
                return response()->json([
                    'status' => false,
                    'message' => 'You min amount must be $' . $code->min_amount . '.',
                ]);
            }
        }

        session()->put('code', $code);

        return $this->getOrderSummery($request);

    }

    public function removeCoupon(Request $request)
    {
        session()->forget('code');

        return $this->getOrderSummery($request);
    }
}
