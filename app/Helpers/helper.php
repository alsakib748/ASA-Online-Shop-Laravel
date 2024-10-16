<?php

use App\Models\Page;
use App\Models\User;
use App\Models\Order;
use App\Models\Country;
use App\Mail\OrderEmail;
use App\Models\Category;
use App\Models\ProductImage;

function getCategories()
{
    return Category::orderBy('name', 'ASC')
        ->with('sub_category')
        ->where('status', 1)
        ->where('showHome', 'Yes')
        ->orderBy('id', 'DESC')
        ->get();
}

function getProductImage($productId)
{
    return ProductImage::where('product_id', $productId)->first();
}

function orderEmail($orderId, $userType = "customer")
{
    $order = Order::where('id', $orderId)->with('items')->first();
    $email = '';

    if ($userType == 'customer') {
        $subject = 'Thanks for your order';
        $email = $order->email;
    } else {
        $adminEmail = User::select('email')->where('role','admin')->where('status',1)->first();
        $subject = 'You have received an order';
        // $email = env("ADMIN_EMAIL");
        $email = $adminEmail->email;
    }

    // dd($email);

    $mailData = [
        'subject' => $subject,
        'order' => $order,
        'userType' => $userType
    ];

    Mail::to($email)->send(new OrderEmail($mailData));

}
function getCountryInfo($id)
{
    return Country::where('id', $id)->first();
}

function staticPages()
{
    $pages = Page::orderBy('name', 'ASC')->get();

    return $pages;
}
