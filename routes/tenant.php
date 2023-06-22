<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
], function () {
Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/test', function () {
       dd(\App\Models\User::all());
        return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
    });
    
        //group 1
        Route::group(
            ['namespace' => 'Dashboard', 'middleware' => 'guest:admin', 'prefix' => 'admin'],
            function () {
                Route::get('login', [App\Http\Controllers\Dashboard\LoginController::class, 'login'])->name('admin.login');
                Route::post('login', [App\Http\Controllers\Dashboard\LoginController::class, 'postlogin'])->name('admin.post.login');
            }
        );
        //end group 1

        
/////// group 2
    Route::group(
        ['namespace' => 'Dashboard', 'middleware' => 'auth:admin', 'prefix' => 'admin'],
        function () {
            Route::get('/', [App\Http\Controllers\Dashboard\DashboardController::class, 'index'])->name('admin.dashboard');
            Route::get('logout', [App\Http\Controllers\Dashboard\LogoutController::class, 'logout'])->name('admin.logout');

            Route::group(
                ['prefix' => 'settings', 'middleware' => 'can:settings'],
                function () {
                            Route::get('shipping-methods/{type}', [App\Http\Controllers\Dashboard\SettingController::class, 'editShippingMethods'])->name('edit.shippings.methods');
                            Route::put('shipping-methods/update-shipping-methods/{id}', [App\Http\Controllers\Dashboard\SettingController::class, 'updateShippingMethods'])->name('update.shippings.methods');

                            /// sliders routes///
                            Route::group(
                                ['prefix' => 'Sliders', 'middleware' => 'can:sliders'],
                                function () {
                                    Route::get('index', [App\Http\Controllers\Dashboard\OptionController::class, 'index'])->name('index.option');
                                    Route::get('create', [App\Http\Controllers\Dashboard\SliderController::class, 'addImages'])->name('admin.sliders.create');
                                    Route::post('images', [App\Http\Controllers\Dashboard\SliderController::class, 'saveSliderImages'])->name('admin.sliders.images.store'); // save imgs to folder
                                    Route::post('images/db', [App\Http\Controllers\Dashboard\SliderController::class, 'saveSliderImagesDB'])->name('admin.sliders.images.store.db');
                                }
                            ); /////////// end sliders routes///////
                        }
            );

            Route::group(
                ['prefix' => 'profile', 'middleware' => 'auth:admin'],
                function () {
                            Route::get('CurrentPassword', [App\Http\Controllers\Dashboard\ProfileController::class, 'GetCurrentPassword'])->name('CurrentPassword');
                            Route::get('edit', [App\Http\Controllers\Dashboard\ProfileController::class, 'Verify'])->name('password.verify');
                            Route::put('update', [App\Http\Controllers\Dashboard\ProfileController::class, 'update'])->name('update.profile');
                        }
            );
            ///////////// categories routes///////
            Route::group(
                ['prefix' => 'Categories', 'middleware' => 'can:categories'],
                function () {
                    Route::get('index', [App\Http\Controllers\Dashboard\CategoryController::class, 'index'])->name('index.category');
                    Route::get('create', [App\Http\Controllers\Dashboard\CategoryController::class, 'create'])->name('create.category');
                    Route::post('store', [App\Http\Controllers\Dashboard\CategoryController::class, 'store'])->name('store.category');
                    Route::get('edit/{id}', [App\Http\Controllers\Dashboard\CategoryController::class, 'edit'])->name('edit.category');
                    Route::post('update/{id}', [App\Http\Controllers\Dashboard\CategoryController::class, 'update'])->name('update.category');
                    Route::get('delete/{id}', [App\Http\Controllers\Dashboard\CategoryController::class, 'delete'])->name('delete.category');
                }
            );
            /////////// end categories routes//////
    
            ///////////// Brands routes///////
            Route::group(
                ['prefix' => 'Brands', 'middleware' => 'can:brands'],
                function () {
                    Route::get('index', [App\Http\Controllers\Dashboard\BrandController::class, 'index'])->name('index.brand');
                    Route::get('create', [App\Http\Controllers\Dashboard\BrandController::class, 'create'])->name('create.brand');
                    Route::post('store', [App\Http\Controllers\Dashboard\BrandController::class, 'store'])->name('store.brand');
                    Route::get('edit/{id}', [App\Http\Controllers\Dashboard\BrandController::class, 'edit'])->name('edit.brand');
                    Route::post('update/{id}', [App\Http\Controllers\Dashboard\BrandController::class, 'update'])->name('update.brand');
                    Route::get('delete/{id}', [App\Http\Controllers\Dashboard\BrandController::class, 'delete'])->name('delete.brand');
                }
            );
            /////////// end Brands routes///////
    
            ///////////// tags routes///////
            Route::group(
                ['prefix' => 'Tags', 'middleware' => 'can:tags'],
                function () {
                    Route::get('index', [App\Http\Controllers\Dashboard\TagsController::class, 'index'])->name('index.tag');
                    Route::get('create', [App\Http\Controllers\Dashboard\TagsController::class, 'create'])->name('create.tag');
                    Route::post('store', [App\Http\Controllers\Dashboard\TagsController::class, 'store'])->name('store.tag');
                    Route::get('edit/{id}', [App\Http\Controllers\Dashboard\TagsController::class, 'edit'])->name('edit.tag');
                    Route::post('update/{id}', [App\Http\Controllers\Dashboard\TagsController::class, 'update'])->name('update.tag');
                    Route::get('delete/{id}', [App\Http\Controllers\Dashboard\TagsController::class, 'delete'])->name('delete.tag');
                }
            );
            /////////// end tags routes///////
    
            ///////////// product routes///////
            Route::group(
                ['prefix' => 'Products', 'middleware' => 'can:products'],
                function () {
                    Route::get('index', [App\Http\Controllers\Dashboard\ProductController::class, 'index'])->name('index.product');
                    Route::get('create', [App\Http\Controllers\Dashboard\ProductController::class, 'create'])->name('create.product');
                    Route::post('store', [App\Http\Controllers\Dashboard\ProductController::class, 'store'])->name('store.product');
                    Route::get('delete/{id}', [App\Http\Controllers\Dashboard\ProductController::class, 'delete'])->name('delete.product');
                    Route::get('price/{id?}', [App\Http\Controllers\Dashboard\ProductController::class, 'getPrice'])->name('price.product');
                    Route::post('price', [App\Http\Controllers\Dashboard\ProductController::class, 'postPrice'])->name('price.store.product');
                    Route::get('stock/{id}', [App\Http\Controllers\Dashboard\ProductController::class, 'getStock'])->name('admin.products.stock');
                    Route::post('stock', [App\Http\Controllers\Dashboard\ProductController::class, 'saveProductStock'])->name('admin.products.stock.store');
                    Route::get('images/{id}', [App\Http\Controllers\Dashboard\ProductController::class, 'addImages'])->name('admin.products.images');
                    Route::post('images', [App\Http\Controllers\Dashboard\ProductController::class, 'saveProductImages'])->name('admin.products.images.store'); // save imgs to folder
                    Route::post('images/db', [App\Http\Controllers\Dashboard\ProductController::class, 'saveProductImagesDB'])->name('admin.products.images.store.db');
                    Route::get('Products_Images/{id}', [App\Http\Controllers\Dashboard\ProductController::class, 'ImageIndex'])->name('admin.products.images.all');
                    Route::get('ProductsImages_delete/{id}', [App\Http\Controllers\Dashboard\ProductController::class, 'delete'])->name('delete.image.product');
                    ///////////// Attributes routes///////
        
                    Route::group(
                        ['prefix' => 'Attributes', 'middleware' => 'can:attributes'],
                        function () {
                                        Route::get('index', [App\Http\Controllers\Dashboard\AtrributeController::class, 'index'])->name('index.attribute');
                                        Route::get('create', [App\Http\Controllers\Dashboard\AtrributeController::class, 'create'])->name('create.attribute');
                                        Route::post('store', [App\Http\Controllers\Dashboard\AtrributeController::class, 'store'])->name('store.attribute');
                                        Route::get('edit/{id}', [App\Http\Controllers\Dashboard\AtrributeController::class, 'edit'])->name('edit.attribute');
                                        Route::post('update/{id}', [App\Http\Controllers\Dashboard\AtrributeController::class, 'update'])->name('update.attribute');
                                        Route::get('delete/{id}', [App\Http\Controllers\Dashboard\AtrributeController::class, 'delete'])->name('delete.attribute');
                                        ///////////// options routes///////
                                        Route::group(
                                            ['prefix' => 'Options', 'middleware' => 'can:options'],
                                            function () {
                                                Route::get('index', [App\Http\Controllers\Dashboard\OptionController::class, 'index'])->name('index.option');
                                                Route::get('create', [App\Http\Controllers\Dashboard\OptionController::class, 'create'])->name('create.option');
                                                Route::post('store', [App\Http\Controllers\Dashboard\OptionController::class, 'store'])->name('store.option');
                                                Route::get('edit/{id}', [App\Http\Controllers\Dashboard\OptionController::class, 'edit'])->name('edit.option');
                                                Route::post('update/{id}', [App\Http\Controllers\Dashboard\OptionController::class, 'update'])->name('update.option');
                                                Route::get('changeStatus/{id}', [App\Http\Controllers\Dashboard\OptionController::class, 'changeStatus'])->name('changeStatus.option');
                                            }
                                        ); /////////// end options routes///////
                                        /////////// end product routes///////
                                    }
                    );
                }
            );


            Route::group(
                ['prefix' => 'users', 'middleware' => ['auth', 'can:users']],
                function () {
                            Route::get('/', [App\Http\Controllers\Dashboard\UsersController::class, 'index'])->name('admin.users.index');
                            Route::get('/create', [App\Http\Controllers\Dashboard\UsersController::class, 'create'])->name('admin.users.create');
                            Route::post('/store', [App\Http\Controllers\Dashboard\UsersController::class, 'store'])->name('admin.users.store');
                        }
            );

            ################################## roles ######################################
            Route::group(
                ['prefix' => 'roles', 'middleware' => 'auth:admin'],
                function () {
                    Route::get('/', [App\Http\Controllers\Dashboard\RolesController::class, 'index'])->name('admin.roles.index');
                    Route::get('create', [App\Http\Controllers\Dashboard\RolesController::class, 'create'])->name('admin.roles.create');
                    Route::post('store', [App\Http\Controllers\Dashboard\RolesController::class, 'saveRole'])->name('admin.roles.store');
                    Route::get('/edit/{id}', [App\Http\Controllers\Dashboard\RolesController::class, 'edit'])->name('admin.roles.edit');
                    Route::post('update/{id}', [App\Http\Controllers\Dashboard\RolesController::class, 'update'])->name('admin.roles.update');
                    ################################## end roles ######################################
        

                }
            );

        }
    );
    //end group2

    ///////// site routes

    Auth::routes();
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




Route::group(['namespace' => 'Site', 'middleware' => 'auth'], function () {
    // must be authenticated user
    Route::post('verify-user/', [App\Http\Controllers\Site\VerificationCodeController::class,'verify'])->name('verify-user');
    Route::get('verify', [App\Http\Controllers\Site\VerificationCodeController::class,'getVerifyPage'])->name('get.verification.form');
    Route::get('products/{productId}/reviews', [App\Http\Controllers\Site\ProductReviewController::class,'index'])->name('products.reviews.index');
    Route::post('products/{productId}/reviews', [App\Http\Controllers\Site\ProductReviewController::class,'store'])->name('products.reviews.store');
    Route::get('payment_amount/{amount}', [App\Http\Controllers\Site\PaymentController::class,'getPayments']) -> name('payment');
    Route::post('payment', [App\Http\Controllers\Site\PaymentController::class,'processPayment']) -> name('payment.process');

    Route::post('wishlist', [App\Http\Controllers\Site\WishlistController::class,'store'])->name('wishlist.store');
    Route::delete('wishlist', [App\Http\Controllers\Site\WishlistController::class,'destroy'])->name('wishlist.delete');
    Route::get('wishlist/products', [App\Http\Controllers\Site\WishlistController::class,'index'])->name('wishlist.index');

});

});
 //////end site routes
});