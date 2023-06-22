<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
       $products =  auth()->user()->WishListsProducts()
        ->latest()->get();
        return view ('front.wishlist.wishlist' , compact('products'));

    }
    public function store(){
        if(! auth()->user()-> WishListHas(request('productId'))){
          auth()->user()-> WishListsProducts()->attach(request('productId'));
          return response() -> json(['status' => true , 'wished' => true]);
        }
        return response() -> json(['status' => true , 'wished' => false]);  // added before we can use enumeration here
    
    }


    public function destroy()
    {
       auth()->user()->WishListsProducts()->detach(request('productId'));
    }

}