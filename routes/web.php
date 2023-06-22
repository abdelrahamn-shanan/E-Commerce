<?php

use Illuminate\Support\Facades\Route;

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

Route::get('test', function () {

    $category =  \App\Models\Category::with('trees')->find(35);



     return $category;
});


Route::get('test', function () {
    $order =  App\Models\Order::create([
        'customer_id' => 5,
        'customer_phone' => '0555555',
        'customer_name' => 'abdo',
        'total' => '1000',
        'locale' => 'en',
        'payment_method' => 2,  // you can use enumeration here as we use before for best practices for constants.
        'status' => App\Models\Order::PAID,
    ]);
    event(new App\Events\NewOrder($order));
    return "Event has been sent!";
});




Route::get('/home', [App\Http\Controllers\Site\HomeController::class,'index'])->name('home');