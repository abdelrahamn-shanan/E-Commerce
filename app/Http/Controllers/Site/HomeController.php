<?php

namespace App\Http\Controllers\Site;

use App\Models\Slider;
use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home(){
       $data=[];
       $data['images'] = Slider::get('image');
       $data['categories'] = Category::Parent()->select('id','slug' ,'photo')->with(['MainChild'=>function($q){
           $q->select('id','parent_id','slug','photo')->with(['MainChild'=>function($qq){
            $qq->select('id','parent_id','slug','photo'); 
           }]);
       }])->get();
        return view('front.home')->with($data);
    }
}
