<?php

use Illuminate\Support\Facades\Route;
use App\Basket\Basket;



    /**
     * Create a new CartController instance.
     *
     * @param Basket $basket
     * @param Product $product
     */
   
/*
|--------------------------------------------------------------------------
| site Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
], function () {


    Route::group(['namespace' => 'Site'/*, 'middleware' => 'guest'*/], function () {
        //guest  user
        Route::get('fat','PaymentController@fatoorah');
        route::get('/', [App\Http\Controllers\Site\HomeController::class, 'home'])->name('home')->middleware('VerifiedUser');
        route::get('category/{slug}', [App\Http\Controllers\Site\CategoryController::class,'productsBySlug'])->name('category');
        route::get('product/{slug}', [App\Http\Controllers\Site\ProductController::class,'productsBySlug'])->name('product.details');

        /**
         *  Cart routes
         */
        Route::group(['prefix' => 'cart'], function () {
            Route::get('/', [App\Http\Controllers\Site\CartController::class, 'getIndex'])->name('site.cart.index');
            Route::post('/add/{product_slug?}', [App\Http\Controllers\Site\CartController::class,'postAdd'])->name('site.cart.add');
            Route::post('/update/{slug}', [App\Http\Controllers\Site\CartController::class , 'postUpdate'])->name('site.cart.update');
            Route::post('/update-all', [App\Http\Controllers\Site\CartController::class,'postUpdateAll'])->name('site.cart.update-all');
        });
    });


    Route::group(['namespace' => 'Site', 'middleware' => ['auth', 'VerifiedUser']], function () {
        // must be authenticated user and verified
        Route::get('profile', function () {
            return 'You Are Authenticated ';
        });
    });

    Route::group(['namespace' => 'Site', 'middleware' => 'auth'], function () {
        // must be authenticated user
        Route::post('verify-user/', [App\Http\Controllers\Site\VerificationCodeController::class,'verify'])->name('verify-user');
        Route::get('verify', [App\Http\Controllers\Site\VerificationCodeController::class,'getVerifyPage'])->name('get.verification.form');
        Route::get('products/{productId}/reviews', [App\Http\Controllers\Site\ProductReviewController::class,'index'])->name('products.reviews.index');
        Route::post('products/{productId}/reviews', [App\Http\Controllers\Site\ProductReviewController::class,'store'])->name('products.reviews.store');
        Route::get('payment_amount', [App\Http\Controllers\Site\PaymentController::class,'getPayments']) -> name('payment');
        Route::post('payment', [App\Http\Controllers\Site\PaymentController::class,'processPayment']) -> name('payment.process');

    });

});

Route::group(['namespace' => 'Site', 'middleware' => 'auth'], function () {
    Route::post('wishlist', [App\Http\Controllers\Site\WishlistController::class,'store'])->name('wishlist.store');
    Route::delete('wishlist', [App\Http\Controllers\Site\WishlistController::class,'destroy'])->name('wishlist.delete');
    Route::get('wishlist/products', [App\Http\Controllers\Site\WishlistController::class,'index'])->name('wishlist.index');
});