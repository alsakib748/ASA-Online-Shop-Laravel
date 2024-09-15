<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontController extends Controller
{

    public function index()
    {
        $featuredProducts = Product::where('is_featured', 'Yes')->where('status', 1)->orderBy('id', 'DESC')->take(8)->get();

        $latestProducts = Product::orderBy('id', 'DESC')->where('status', 1)->take(8)->get();

        return view('front.home', compact(['featuredProducts', 'latestProducts']));
    }

    public function addToWishList(Request $request)
    {

        if (Auth::check() == false) {

            session(['url.intended' => url()->previous()]);

            return response()->json([
                'status' => false
            ]);


        }
    }
}