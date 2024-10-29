<?php

namespace App\Http\Controllers;

use App\Models\CustomerAddress;
use App\Models\User;
use App\Models\Order;
use App\Models\Country;
use App\Models\WishList;
use App\Models\OrderItem;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // todo: user orders show
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

    // todo; user wishlist

    public function wishlist()
    {

        $wishlists = WishList::where('user_id', Auth::user()->id)->with('product')->get();

        return view('front.account.wishlist', compact('wishlists'));
    }

    public function removeProductFromWishList(Request $request)
    {
        $wishlist = Wishlist::where('user_id', Auth::user()->id)->where('product_id', $request->id)->first();

        if ($wishlist == null) {

            session()->flash('error', 'Product already removed');

            return response()->json([
                'status' => true,
            ]);
        } else {
            WishList::where('user_id', Auth::user()->id)->where('product_id', $request->id)->delete();

            session()->flash('success', 'Product removed successfully');

            return response()->json([
                'status' => true,
            ]);
        }

    }


    // todo; user profile

    public function userProfile()
    {
        $userId = Auth::user()->id;

        $countries = Country::orderBy('name', 'ASC')->get();

        $userData = User::where('id', $userId)->first();

        $address = CustomerAddress::where('user_id', $userId)->first();

        return view('dashboard', compact(['userData', 'countries', 'address']));
    }

    public function updateProfile(Request $request)
    {
        $userId = Auth::user()->id;

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $userId . ',id',
            'phone' => 'required'
        ]);

        if ($validator->passes()) {

            $user = User::find($userId);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->save();

            session()->flash('success', 'Profile Updated Successfully');

            return response()->json([
                'status' => true,
                'message' => 'Profile Updated Successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function updateAddress(Request $request)
    {
        $userId = Auth::user()->id;

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:5',
            'last_name' => 'required',
            'email' => 'required|email',
            'country_id' => 'required',
            'address' => 'required|min:30',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'mobile' => 'required'
        ]);

        if ($validator->passes()) {

            // $user = User::find($userId);
            // $user->name = $request->name;
            // $user->email = $request->email;
            // $user->phone = $request->phone;
            // $user->save();


            CustomerAddress::updateOrCreate(
                ['user_id' => $userId],
                [
                    'user_id' => $userId,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'mobile' => $request->mobile,
                    'country_id' => $request->country_id,
                    'address' => $request->address,
                    'apartment' => $request->apartment,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip' => $request->zip
                ]
            );

            session()->flash('success', 'User Address Updated Successfully');

            return response()->json([
                'status' => true,
                'message' => 'User Address Updated Successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function showChangePasswordForm()
    {

        return view('front.account.change-password');
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password'
        ]);

        if ($validator->passes()) {

            $user = User::select('id', 'password')->where('id', Auth::user()->id)->first();

            if (!Hash::check($request->old_password, $user->password)) {

                session()->flash('error', 'Your old password is incorrect, please try again');

                return response()->json([
                    'status' => true,
                    'message' => 'Your old password is incorrect, please try again'
                ]);

            }

            User::where('id', $user->id)->update([
                'password' => Hash::make($request->new_password)
            ]);

            session()->flash('success', 'Your have successfully changed your password');

            return response()->json([
                'status' => true,
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function UserLogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');

    }

}
