<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use  App\Models\Product;
use  App\Models\Attribute;

class ProductController extends Controller
{
    public function productsBySlug($slug){
        $data=[];
        $data['product'] = Product::whereSlug($slug)->first(); // the best is search by slug
        if(!  $data['product'] ){
            return redirect()->route('home')->with(['error'=>'Slug Not Found']);
        }
        $product_id =  $data['product']->id; 
        $data['product_attributes']= Attribute::whereHas('options',function($q) use($product_id){
           $q->whereHas('products',function($qq) use($product_id){
               $qq->where('product_id',$product_id );
           });
       })->get();   
        
         $Product_categories_ids = $data['product']->categories->pluck('id'); // get categories ids that contain this product [1,2,3]
           $data['related_products']= Product::where('id','!=',$data['product']->id)
          ->whereHas('categories',function($cat) use ( $Product_categories_ids){
          $cat->whereIn('categories.id',$Product_categories_ids);
      }) -> limit(2) -> latest() ->get();
        return view('front.products.product-details',$data);   
    }
}
