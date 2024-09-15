<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    //
    public function orders()
    {
        $user = Auth::user();

        $orders = Order::where('user_id', $user->id)->orderBy('created_at', 'DESC')->get();

        $user = Auth::user();

        return view('front.account.order', compact('orders'));
    }

    public function orderDetail($id)
    {

        $user = Auth::user();

        $order = Order::where('user_id', $user->id)->where('id', $id)->first();

        $orderItems = OrderItem::where('order_id', $id)->get();

        $orderItemsCount = OrderItem::where('order_id', $id)->count();

        return view('front.account.order-detail', compact(['order', 'orderItems', 'orderItemsCount']));
    }


    public function UserLogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');

    }


}