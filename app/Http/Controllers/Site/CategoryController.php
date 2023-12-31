<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use  App\Models\Product;

class CategoryController extends Controller
{
    public function ProductsBySlug($slug){
    $data=[];
     $data['category'] = Category::whereSlug($slug)->first();
    if( $data['category'])
           $data['products']=  $data['category']->products;
    return view('front.products.products',$data);         

}
}