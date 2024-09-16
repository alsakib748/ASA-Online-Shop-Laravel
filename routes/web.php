<?php

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\OrderController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\ShippingController;
use App\Http\Controllers\admin\TempImagesController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\DiscountCodeController;
use App\Http\Controllers\admin\ProductImageController;
use App\Http\Controllers\admin\ProductSubCategoryController;

// Route::get('/test', function () {
//     orderEmail(13);
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

// todo: ******************************************************* Admin Panel (Private) Routes *************************************************** //

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::prefix('admin')->group(function () {

        Route::controller(AdminController::class)->group(function () {
            Route::get('/dashboard', 'AdminDashboard')->name('admin.dashboard');
            Route::get('/logout', 'AdminLogout')->name('admin.logout');
        });

        // todo: category controller Route
        Route::controller(CategoryController::class)->group(function () {

            Route::get('/categories', 'index')->name('categories.index');
            Route::get('/categories/create', 'create')->name('categories.create');
            Route::post('/categories/store', 'store')->name('categories.store');
            Route::get('/categories/{category}/edit', 'edit')->name('categories.edit');
            Route::put('/categories/{category}', 'update')->name('categories.update');
            Route::delete('/categories/{category}', 'destory')->name('categories.delete');

            Route::get('/getSlug', function (Request $request) {
                $slug = '';
                if (!empty($request->title)) {
                    $slug = Str::slug($request->title);
                }

                return response()->json([
                    'status' => true,
                    'slug' => $slug
                ]);
            })->name('getSlug');
        });
        // todo: TempImagesController Route 
        Route::controller(TempImagesController::class)->group(function () {

            Route::post('/upload-temp-image', 'create')->name('temp-images.create');
        });
        // todo: SubCategoryController Route
        Route::controller(SubCategoryController::class)->group(function () {
            Route::get('/sub-categories', 'index')->name('sub-categories.index');
            Route::get('/sub-categories/create', 'create')->name('sub-categories.create');
            Route::post('/sub-categories/store', 'store')->name('sub-categories.store');
            Route::get('/sub-categories/{subCategory}/edit', 'edit')->name('sub-categories.edit');
            Route::put('/sub-categories/{subCategory}', 'update')->name('sub-categories.update');
            Route::delete('/sub-categories/{subCategory}', 'destory')->name('sub-categories.delete');
        });
        // todo: Brand Controller Route
        Route::controller(BrandController::class)->group(function () {
            Route::get('/brands', 'index')->name('brands.index');
            Route::get('/brands/create', 'create')->name('brands.create');
            Route::post('/brands', 'store')->name('brands.store');
            Route::get('/brands/{brand}/edit', 'edit')->name('brands.edit');
            Route::put('/brands/{brand}', 'update')->name('brands.update');
            // Route::delete('/categories/{category}','destory')->name('categories.delete');
        });
        // todo: Product Controller Route
        Route::controller(ProductController::class)->group(function () {
            Route::get('/products', 'index')->name('products.index');
            Route::get('/products/create', 'create')->name('products.create');
            Route::post('/products', 'store')->name('products.store');
            Route::get('/products/{product}/edit', 'edit')->name('products.edit');
            Route::put('/products/{product}', 'update')->name('products.update');
            Route::delete('/products/{product}', 'destroy')->name('products.delete');
            Route::get('/get-products', 'getProducts')->name('products.getProducts');
        });

        Route::controller(ProductSubCategoryController::class)->group(function () {
            Route::get('/product-subcategories', 'index')->name('product-subcategories.index');
        });

        Route::controller(ProductImageController::class)->group(function () {
            Route::post('/product-images/update', 'update')->name('product-images.update');
            Route::delete('/product-images', 'destroy')->name('product-images.destroy');
        });

        Route::controller(ShippingController::class)->group(function () {
            Route::get('/shipping/create', 'create')->name('shipping.create');
            Route::post('/shipping', 'store')->name('shipping.store');
            Route::get('/shipping/{id}', 'edit')->name('shipping.edit');
            Route::put('/shipping/{id}', 'update')->name('shipping.update');
            Route::delete('/shipping/{id}', 'destroy')->name('shipping.delete');
        });

        Route::controller(DiscountCodeController::class)->group(function () {
            Route::get('/coupons', 'index')->name('coupons.index');
            Route::get('/coupons/create', 'create')->name('coupons.create');
            Route::post('/coupons', 'store')->name('coupons.store');
            // Route::get('/shipping/{id}', 'edit')->name('shipping.edit');
            // Route::put('/shipping/{id}', 'update')->name('shipping.update');
            // Route::delete('/shipping/{id}', 'destroy')->name('shipping.delete');
        });

        Route::controller(OrderController::class)->group(function () {
            Route::get(uri: '/orders', action: 'index')->name('orders.index');
            Route::get('/orders/{id}', 'detail')->name('orders.detail');
            Route::post('/order/change-status/{id}', 'changeOrderStatus')->name('orders.changeOrderStatus');
            Route::post('/order/send-email/{id}', 'sendInvoiceEmail')->name('orders.sendInvoiceEmail');
            // Route::get('/shipping/{id}', 'edit')->name('shipping.edit');
            // Route::put('/shipping/{id}', 'update')->name('shipping.update');
            // Route::delete('/shipping/{id}', 'destroy')->name('shipping.delete');
        });

        // todo: Admin = user routes
        Route::controller(App\Http\Controllers\admin\UserController::class)->group(function () {
            Route::get(uri: '/users', action: 'index')->name('admin.users.index');
            Route::get('/users/create', 'create')->name('admin.users.create');
            Route::post('/users', 'store')->name('admin.users.store');
            Route::get('/users/{user}/edit', 'edit')->name('admin.users.edit');
            Route::put('/users/{user}', 'update')->name('admin.users.update');
            Route::delete('/users/{user}', 'destroy')->name('admin.users.delete');
        });

    });

});

// todo: ************************************************* User Private Route *******************************************************  //

Route::middleware(['auth', 'role:user'])->group(function () {

    Route::controller(CartController::class)->group(function () {
        Route::get('/checkout', 'checkout')->name('front.checkout');
        Route::post('/process-checkout', 'processCheckout')->name('front.processCheckout');
        Route::get('/thanks/{orderId}', 'thankYou')->name('front.thankYou');
        Route::post('/get-order-summery', 'getOrderSummery')->name('front.getOrderSummery');
        Route::post('/apply-discount', 'applyDiscount')->name('front.applyDiscount');
        Route::post('/remove-discount', 'removeCoupon')->name('front.removeCoupon');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('/dashboard', 'userProfile')->middleware(['auth', 'verified'])->name('dashboard');
        Route::post('/update-profile', 'updateProfile')->name('users.updateProfile');
        Route::post('/update-address', 'updateAddress')->name('users.updateAddress');
        Route::get('/my-orders', 'orders')->name('users.orders');
        Route::get('/order-detail/{orderId}', 'orderDetail')->name('users.orderDetail');
        Route::get('/my-wishlist', 'wishlist')->name('users.wishlist');
        Route::post('/remove-product-from-wishlist', 'removeProductFromWishList')->name('users.removeProductFromWishList');
        Route::get('/logout', 'UserLogout')->name('user.logout');
    });


});

// todo: ************************************************* Public Route *************************************************************  //

Route::get('/login', [AdminController::class, 'AdminLogin'])->name('login');

Route::controller(FrontController::class)->group(function () {
    Route::get('/', 'index')->name('front.home');
    Route::post('/add-to-wishlist', 'addToWishList')->name('front.addToWishList');
});

Route::controller(ShopController::class)->group(function () {
    Route::get('/shop/{categorySlug?}/{subCategorySlug?}', 'index')->name('front.shop');
    Route::get('/product/{slug}', 'product')->name('front.product');
});

Route::controller(CartController::class)->group(function () {
    Route::get('/cart', 'cart')->name('front.cart');
    Route::post('/add-to-cart', 'addToCart')->name('front.addToCart');
    Route::post('/update-cart', 'updateCart')->name('front.updateCart');
    Route::post('/delete-item', 'deleteItem')->name('front.deleteItem.cart');
    Route::get('/checkout', 'checkout')->name('front.checkout');
});