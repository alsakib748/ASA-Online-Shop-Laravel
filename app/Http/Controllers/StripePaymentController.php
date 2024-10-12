<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use Stripe\StripeClient;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Models\ShippingCharge;
use App\Models\CustomerAddress;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Validator;

class StripePaymentController extends Controller
{
    public function stripePayment(Request $request)
    {

        //todo; step - 2 save user address
        $user = Auth::user();

        if ($request->payment_method == 'stripe') {

            // $discountCodeId = null;
            // $shipping = 0;
            // $discount = 0;
            // $promoCode = '';
            // $subTotal = Cart::subtotal(2, '.', '');

            // Apply discount here
            // if (session()->has('code')) {
            //     $code = session()->get('code');

            //     if ($code->type == 'percent') {
            //         $discount = ($code->discount_amount / 100) * $subTotal;
            //     } else {
            //         $discount = $code->discount_amount;
            //     }

            //     $discountCodeId = $code->id;
            //     $promoCode = $code->code;
            // }

            // $customerAddress = CustomerAddress::where('user_id',$user->id)->first();

            // calculate shipping
            // $shippingInfo = ShippingCharge::where('country_id', $customerAddress->country_id)->first();

            // $totalQty = 0;
            // $productNames = [];
            // foreach (Cart::content() as $item) {
            //     $totalQty += $item->qty;
            //     $productNames[] = $item->name . ' X ' . $item->qty;
            // }

            // if ($shippingInfo != null) {

            //     $shipping = $totalQty * $shippingInfo->amount;

            //     $grandTotal = ($subTotal - $discount) + $shipping;

            // } else {
            //     $shippingInfo = ShippingCharge::where('country_id', 'rest_of_world')->first();

            //     $shipping = $totalQty * $shippingInfo->amount;

            //     $grandTotal = ($subTotal - $discount) + $shipping;
            // }

            // $unitAmount = round($grandTotal * 100);

            // // todo: stripe payment gateway integration
            // $stripe = new StripeClient(config('stripe.stripe_sk'));

            // $response = $stripe->checkout->sessions->create([
            //     'line_items' => [
            //         [
            //             'price_data' => [
            //                 'currency' => 'usd',
            //                 'product_data' => [
            //                     'name' => "ASA Online Shop",
            //                 ],
            //                 'unit_amount' => $unitAmount,
            //             ],
            //             'quantity' => 1,
            //         ],
            //     ],
            //     'mode' => 'payment',
            //     'success_url' => route('success') . '?session_id={CHECKOUT_SESSION_ID}',
            //     'cancel_url' => route('cancel'),
            //     'metadata' => [
            //         'subTotal' => $subTotal,
            //         'discount' => $discount,
            //         'shipping' => $shipping,
            //         'grandTotal' => $grandTotal,
            //         'totalQty' => $totalQty,
            //         'country' => $customerAddress->country_id,
            //         'promoCode' => $promoCode ?? null,
            //         'first_name' => $customerAddress->first_name,
            //         'last_name' => $customerAddress->last_name,
            //         'email' => $customerAddress->email,
            //         'mobile' => $customerAddress->mobile,
            //         'address' => $customerAddress->address,
            //         'apartment' => $customerAddress->apartment,
            //         'state' => $customerAddress->state,
            //         'city' => $customerAddress->city,
            //         'zip' => $customerAddress->zip,
            //         'order_notes' => $customerAddress->order_notes,
            //         'product_names' => json_encode($productNames),
            //     ],
            // ]);

            // dd($response);

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
                            'unit_amount' => 200,
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',
                'success_url' => route('success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('cancel'),
            ]);

            // dd($response);

            if (isset($response->id) && $response->id != '') {

                // session()->put('product_name', $productNames);
                // session()->put('quantity', $totalQty);
                // session()->put('price', $grandTotal);

                session()->put('product_name', "T-Shirt");
                session()->put('quantity', 3);
                session()->put('price', 200);

                return redirect($response->url);
            } else {
                return redirect()->route('cancel');
            }

        } // todo: End If

    } // todo: End StripePayment Function

    public function success(Request $request)
    {

        if (isset($request->session_id)) {
            $stripe = new StripeClient(config('stripe.stripe_sk'));

            $response = $stripe->checkout->sessions->retrieve($request->session_id);

            // dd($response);

            // $subTotal = $response->metadata->subTotal;
            // $discount = $response->metadata->discount;
            // $shipping = $response->metadata->shipping;
            // $grandTotal = $response->metadata->grandTotal;
            // $totalQty = $response->metadata->totalQty;
            // $country = $response->metadata->country;
            // $promoCode = $response->metadata->promoCode;
            // $first_name = $response->metadata->first_name;
            // $last_name = $response->metadata->last_name;
            // $email = $response->metadata->email;
            // $mobile = $response->metadata->mobile;
            // $address = $response->metadata->address;
            // $apartment = $response->metadata->apartment;
            // $state = $response->metadata->state;
            // $city = $response->metadata->city;
            // $zip = $response->metadata->zip;
            // $order_notes = $response->metadata->order_notes;

            //todo; step - 2 save user address
            // $user = Auth::user();

            // $discountCodeId = null;
            // $shipping = 0;
            // $discount = 0;
            // $promoCode = '';
            // $subTotal = Cart::subtotal(2, '.', '');

            // Apply discount here
            // if (session()->has('code')) {
            //     $code = session()->get('code');

            //     if ($code->type == 'percent') {
            //         $discount = ($code->discount_amount / 100) * $subTotal;
            //     } else {
            //         $discount = $code->discount_amount;
            //     }

            //     $discountCodeId = $code->id;
            //     $promoCode = $code->code;
            // }

            // calculate shipping
            // $shippingInfo = ShippingCharge::where('country_id', $country)->first();

            // $totalQty = 0;
            // foreach (Cart::content() as $item) {
            //     $totalQty += $item->qty;
            // }

            // if ($shippingInfo != null) {

            //     $shipping = $totalQty * $shippingInfo->amount;

            //     $grandTotal = ($subTotal - $discount) + $shipping;

            // } else {
            //     $shippingInfo = ShippingCharge::where('country_id', 'rest_of_world')->first();

            //     $shipping = $totalQty * $shippingInfo->amount;

            //     $grandTotal = ($subTotal - $discount) + $shipping;
            }

            // todo: Store Order Details
            // $order = new Order();
            // $order->subtotal = Cart::subtotal();
            // $order->subtotal = $subTotal;
            // $order->shipping = $shipping;
            // $order->grand_total = $grandTotal;
            // $order->discount = $discount;
            // $order->coupon_code_id = $discountCodeId;
            // $order->coupon_code = $promoCode;
            // $order->payment_status = 'paid';
            // $order->status = 'pending';
            // $order->user_id = $user->id;
            // $order->first_name = $first_name;
            // $order->last_name = $last_name;
            // $order->email = $email;
            // $order->mobile = $mobile;
            // $order->address = $address;
            // $order->apartment = $apartment;
            // $order->state = $state;
            // $order->city = $city;
            // $order->zip = $zip;
            // $order->notes = $order_notes;
            // $order->country_id = $country;
            // $order->save();

            //todo; step - 4 store order items in order items table

            // foreach (Cart::content() as $item) {
            //     $orderItem = new OrderItem();
            //     $orderItem->product_id = $item->id;
            //     $orderItem->order_id = $order->id;
            //     $orderItem->name = $item->name;
            //     $orderItem->qty = $item->qty;
            //     $orderItem->price = $item->price;
            //     $orderItem->total = $item->price * $item->qty;
            //     $orderItem->save();

            //     // Update Product Stock

            //     $productData = Product::find($item->id);
            //     if ($productData->track_qty == 'Yes') {
            //         $currentQty = $productData->qty;

            //         $updatedQty = $currentQty - $item->qty;

            //         $productData->qty = $updatedQty;

            //         $productData->save();
            //     }

            // }

            // Retrieve product names from session
            // $productNames = session()->get('product_name', []);

            // if (!is_array($productNames)) {
            //     $productNames = json_decode($productNames, true);
            // }

            // $payment = new Payment();
            // $payment->payment_id = $response->id;
            // $payment->product_name = implode(', ', $productNames);
            // $payment->total_quantity = session()->get('quantity');
            // $payment->amount = session()->get('price');
            // $payment->currency = $response->currency;
            // $payment->payer_name = $response->customer_details->name;
            // $payment->payer_email = $response->customer_details->email;
            // $payment->payment_status = $response->status;
            // $payment->payment_method = "Stripe";
            // $payment->save();

            // Send Order Email
            // orderEmail($order->id, 'customer');

            session()->flash("success", "You have successfully placed your order.");

            Cart::destroy();

            // discount code session remove
            session()->forget('code');
            // stripe session remove
            session()->forget('product_name');
            session()->forget('quantity');
            session()->forget('price');

            return response()->json([
                'message' => 'Order saved successfully',
                // 'orderId' => $order->id,
                'status' => true,
            ]);

            // return redirect()->to('/thanks/' . $order->id)->with('notification', $notification);

        // } else {
        //     return redirect()->route('cancel');
        // }

    }

    public function cancel()
    {
        return "Cancel";
    }

}
