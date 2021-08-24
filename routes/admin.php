<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/
// prefix admin

Route::group(
[
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath'],
    ], function () {
        //group 1
        Route::group(['namespace' => 'Dashboard', 'middleware' => 'guest:admin',  'prefix' => 'admin'], function () {
            Route::get('login', 'LoginController@login')->name('admin.login');
            Route::post('login', 'LoginController@postlogin')->name('admin.post.login');
        });
        //end group 1

        ///group 2
        Route::group(['namespace' => 'Dashboard', 'middleware' => 'auth:admin',   'prefix' => 'admin'], function () {
            Route::get('/', 'DashboardController@index')->name('admin.dashboard');
            Route::get('logout', 'LogoutController@logout')->name('admin.logout');

            Route::group(['prefix' => 'settings'], function () {
                Route::get('shipping-methods/{type}', 'SettingController@editShippingMethods')->name('edit.shippings.methods');
                Route::put('shipping-methods/update-shipping-methods/{id}', 'SettingController@updateShippingMethods')->name('update.shippings.methods');
            });

            Route::group(['prefix' => 'profile'], function () {
                Route::get('CurrentPassword', 'ProfileController@GetCurrentPassword')->name('CurrentPassword');
                Route::get('edit', 'ProfileController@Verify')->name('password.verify');
                Route::put('update', 'ProfileController@update')->name('update.profile');
            });
            ///////////// categories routes///////
            Route::group(['prefix' => 'Categories'], function () {
                Route::get('index', 'CategoryController@index')->name('index.category');
                Route::get('create', 'CategoryController@create')->name('create.category');
                Route::post('store', 'CategoryController@store')->name('store.category');
                Route::get('edit/{id}', 'CategoryController@edit')->name('edit.category');
                Route::post('update/{id}', 'CategoryController@update')->name('update.category');
                Route::get('delete/{id}', 'CategoryController@delete')->name('delete.category');
            });
            /////////// end categories routes//////

            ///////////// Brands routes///////
            Route::group(['prefix' => 'Brands'], function () {
                Route::get('index', 'BrandController@index')->name('index.brand');
                Route::get('create', 'BrandController@create')->name('create.brand');
                Route::post('store', 'BrandController@store')->name('store.brand');
                Route::get('edit/{id}', 'BrandController@edit')->name('edit.brand');
                Route::post('update/{id}', 'BrandController@update')->name('update.brand');
                Route::get('delete/{id}', 'BrandController@delete')->name('delete.brand');
            });
            /////////// end Brands routes///////

            ///////////// tags routes///////
            Route::group(['prefix' => 'Tags'], function () {
                Route::get('index', 'TagsController@index')->name('index.tag');
                Route::get('create', 'TagsController@create')->name('create.tag');
                Route::post('store', 'TagsController@store')->name('store.tag');
                Route::get('edit/{id}', 'TagsController@edit')->name('edit.tag');
                Route::post('update/{id}', 'TagsController@update')->name('update.tag');
                Route::get('delete/{id}', 'TagsController@delete')->name('delete.tag');
            });
            /////////// end tags routes///////

            ///////////// product routes///////
            Route::group(['prefix' => 'Products'], function () {
                Route::get('index', 'ProductController@index')->name('index.product');
                Route::get('create', 'ProductController@create')->name('create.product');
                Route::post('store', 'ProductController@store')->name('store.product');
                Route::get('delete/{id}', 'ProductController@delete')->name('delete.product');
                Route::get('price/{id?}', 'ProductController@getPrice')->name('price.product');
                Route::post('price', 'ProductController@postPrice')->name('price.store.product');
                Route::get('stock/{id}', 'ProductController@getStock')->name('admin.products.stock');
                Route::post('stock', 'ProductController@saveProductStock')->name('admin.products.stock.store');
                Route::get('images/{id}', 'ProductController@addImages')->name('admin.products.images');
                Route::post('images', 'ProductController@saveProductImages')->name('admin.products.images.store'); // save imgs to folder
                Route::post('images/db', 'ProductController@saveProductImagesDB')->name('admin.products.images.store.db');
                Route::get('Products_Images/{id}', 'ProductController@ImageIndex')->name('admin.products.images.all');
                Route::get('ProductsImages_delete/{id}', 'ProductController@delete')->name('delete.image.product');
            ///////////// Attributes routes///////

                Route::group(['prefix' => 'Attributes'], function () {
                    Route::get('index', 'AtrributeController@index')->name('index.attribute');
                    Route::get('create', 'AtrributeController@create')->name('create.attribute');
                    Route::post('store', 'AtrributeController@store')->name('store.attribute');
                    Route::get('edit/{id}', 'AtrributeController@edit')->name('edit.attribute');
                    Route::post('update/{id}', 'AtrributeController@update')->name('update.attribute');
                    Route::get('delete/{id}', 'AtrributeController@delete')->name('delete.attribute');
                       ///////////// options routes///////
                Route::group(['prefix' => 'Options'], function () {
                    Route::get('index', 'OptionController@index')->name('index.option');
                    Route::get('create', 'OptionController@create')->name('create.option');
                    Route::post('store', 'OptionController@store')->name('store.option');
                    Route::get('edit/{id}', 'OptionController@edit')->name('edit.option');
                    Route::post('update/{id}', 'OptionController@update')->name('update.option');
                    Route::get('changeStatus/{id}', 'OptionController@changeStatus')->name('changeStatus.option');
                });  /////////// end options routes///////
                
                });             /////////// end Attributes routes///////
                   

            });
            /////////// end product routes///////

           
        });
        //end group2
    });
