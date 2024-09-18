<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductRating;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
{
    //
    public function index(Request $request, $categorySlug = null, $subCategorySlug = null)
    {
        $categorySelected = '';
        $subCategorySelected = '';
        $brandsArray = [];

        // if (!empty($request->get('brand'))) {
        //     $brandsArray = explode(',', $request->get('brand'));
        // }

        $categories = Category::orderBy('name', 'ASC')->with('sub_category')->where('status', 1)->get();

        $brands = Brand::orderBy('name', 'ASC')->where('status', 1)->get();

        $products = Product::where('status', 1);
        // Apply Filters Here

        if (!empty($categorySlug)) {
            $category = Category::where('slug', $categorySlug)->first();
            $products = $products->where('category_id', $category->id);
            $categorySelected = $category->id;
        }

        // filter products by category
        if (!empty($subCategorySlug)) {
            $subCategory = SubCategory::where('slug', $subCategorySlug)->first();
            $products = $products->where('sub_category_id', $subCategory->id);
            $subCategorySelected = $subCategory->id;
        }

        // filter products by brand
        if (!empty($request->get('brand'))) {
            $brandsArray = explode(',', $request->get('brand'));
            $products = $products->whereIn('brand_id', $brandsArray);
        }

        // filter products by price
        if ($request->get('price_max') != '' & $request->get('price_min') != '') {
            if ($request->get('price_max') == 1000) {
                $products = $products->whereBetween('price', [intval($request->get('price_min')), 1000000]);
            } else {
                $products = $products->whereBetween('price', [intval($request->get('price_min')), intval($request->get('price_max'))]);
            }
        }

        // search products
        if (!empty($request->get("search"))) {
            $products = $products->where('title', 'like', '%' . $request->get("search") . '%');
        }

        // sort products
        if ($request->get('sort') != '') {
            if ($request->get('sort') == 'latest') {
                $products = $products->orderBy('id', 'DESC');
            } else if ($request->get('sort') == 'price_asc') {
                $products = $products->orderBy('price', 'ASC');
            } else {
                $products = $products->orderBy('price', 'DESC');
            }
        } else {
            $products = $products->orderBy('id', 'DESC');
        }

        $products = $products->paginate(6);

        $data['priceMax'] = (intval($request->get('price_max')) == 0) ? 1000 : intval($request->get('price_max'));
        $data['priceMin'] = intval($request->get('price_min'));
        $data['sort'] = $request->get('sort');

        return view('front.shop', compact(['categories', 'brands', 'products', 'categorySelected', 'subCategorySelected', 'brandsArray']), $data);
    }

    public function product($slug)
    {
        // todo: Product ratings 
        $product = Product::where('slug', $slug)
            ->withCount('product_ratings')
            ->withSum('product_ratings', 'rating')
            ->with(['product_images', 'product_ratings'])
            ->first();

        if ($product == null) {
            abort(404);
        }

        // fetch related products
        $relatedProducts = [];
        if ($product->related_products != "") {
            $productArray = explode(",", $product->related_products);

            $relatedProducts = Product::whereIn("id", $productArray)->where('status', 1)->get();
        }

        // *** Rating Calculation 

        $avgRating = '0.00';

        $avgRatingPer = 0;

        if ($product->product_ratings_count > 0) {
            $avgRating = number_format(($product->product_ratings_sum_rating / $product->product_ratings_count), 2);

            $avgRatingPer = ($avgRating * 100) / 5;
        }


        return view('front.product', compact(['product', 'relatedProducts', 'avgRating', 'avgRatingPer']));
    }

    public function saveRating(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:5',
            'email' => 'required|email',
            'comment' => 'required|min:10',
            'rating' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        $count = ProductRating::where('product_id', $id)->where('email', $request->email)->count();

        if ($count > 0) {
            session()->flash('error', 'You already rated this product.');
            return response()->json([
                'status' => true
            ]);
        }

        $productRating = new ProductRating();
        $productRating->product_id = $id;
        $productRating->username = $request->name;
        $productRating->email = $request->email;
        $productRating->rating = $request->rating;
        $productRating->comment = $request->comment;
        $productRating->status = 0;
        $productRating->save();

        session()->flash('success', 'Thanks for your review & rating');

        return response()->json([
            'status' => true,
            'message' => 'Thanks for your review & rating'
        ]);



    }

}