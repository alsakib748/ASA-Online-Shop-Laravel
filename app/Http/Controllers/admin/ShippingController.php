<?php

namespace App\Http\Controllers\admin;

use App\Models\Country;
use App\Models\ShippingCharge;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ShippingController extends Controller
{
    //
    public function create()
    {
        $countries = Country::orderBy('name', 'ASC')->get();

        $shippingCharges = ShippingCharge::select('shipping_charges.*', 'countries.name')->leftJoin('countries', 'countries.id', 'shipping_charges.country_id')->get();

        return view('admin.shipping.create', compact(['countries', 'shippingCharges']));
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'country' => 'required',
            'amount' => 'required|numeric'
        ]);

        if ($validator->passes()) {

            $count = ShippingCharge::where('country_id', $request->country)->count();

            if ($count > 0) {
                session()->flash("error", "Shipping already added");
                return response()->json([
                    'status' => true,
                ]);
            }

            $shipping = new ShippingCharge();
            $shipping->country_id = $request->country;
            $shipping->amount = $request->amount;
            $shipping->save();

            session()->flash("success", "Shipping added successfully.");

            return response()->json([
                'status' => true
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($id)
    {

        $shippingCharge = ShippingCharge::find($id);

        $countries = Country::orderBy('name', 'ASC')->get();

        return view('admin.shipping.edit', compact(['countries', 'shippingCharge']));

    }

    public function update($id, Request $request)
    {

        $validator = Validator::make($request->all(), [
            'country' => 'required',
            'amount' => 'required|numeric'
        ]);

        if ($validator->passes()) {

            $shipping = ShippingCharge::find($id);

            if ($shipping == null) {
                session()->flash("error", "Shipping not found");

                return response()->json([
                    'status' => true
                ]);
            }

            $shipping->country_id = $request->country;
            $shipping->amount = $request->amount;
            $shipping->save();

            session()->flash("success", "Shipping Updated successfully.");

            return response()->json([
                'status' => true
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

    }

    public function destroy($id)
    {
        $shippingCharge = ShippingCharge::find($id);

        if ($shippingCharge == null) {
            session()->flash("error", "Shipping not found");

            return response()->json([
                'status' => true
            ]);
        }

        $shippingCharge->delete();

        session()->flash("success", "Shipping deleted successfully.");

        return response()->json([
            'status' => true
        ]);
    }

}