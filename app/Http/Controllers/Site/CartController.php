<?php
namespace App\Http\Controllers\Site;

use App\Models\Product;
use App\Http\Requests;
use App\Basket\Basket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exceptions\QuantityExceededException;
use DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;

class CartController extends Controller
{
        /**
     * Instance of Basket.
     *
     * @var Basket
     */
    protected $basket;
    protected $id;

    /**
     * Create a new CartController instance.
     *
     * @param Basket $basket
     * @param Product $product
     */
    public function __construct(Basket $basket)
    {
        $this->basket = $basket;

    }

    /**
     * Show all items in the Basket.
     *
     */

    public function getIndex()
    {
         $basket = $this -> basket ;

        return view('front.cart.index',compact('basket'));
    }

    /**
     * Add items to the Basket.
     *
     * @param $slug
     * @param $quantity
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postAdd(Request $request)
    {
        
         $slug =$request -> product_slug ;
         $product = Product::where('slug', $slug)->firstOrFail();

        try {
            $this->basket->add($product, $request->quantity ?? 1);
        } catch (QuantityExceededException $e) {
            return 'Quantity Exceeded';  // must be trans as the site is multi languages
        }

        return 'Product added successfully to the card ';
    }

    /**
     * Update the Basket item with given slug.
     *
     * @param         $slug
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \App\Exceptions\QuantityExceededException
     */
    public function postUpdate(Request $request)
    {
        if($request->Ajax()){
            $product_slug=$request->product_slug;
            $product = Product::where('slug',$product_slug)->firstOrFail();
            try {
                $this->basket->update($product, $request->quantity);
                return redirect()->back()->with(['success'=>"تم حذف المنتج بنجاح من سلة المشتريات"]);
            } catch (QuantityExceededException $e) {
                return redirect()->back()->with(['errors'=>"حدث خطأ ما"]);
            }


        }
    }
}
/*
        if(Ajax())
        $product_slug=
        $product = Product::where('slug', $slug)->firstOrFail();
        if(!$product)
        return redirect()->back()->with(['success'=>"تم حذف المنتج بنجاح من سلة المشتريات"]);

        try {
            $this->basket->update($_product, $request->quantity);
        } catch (QuantityExceededException $e) {
            return trans('site.cart.msgs.exceeded');
        }

        if (!$request->quantity) {
            return array_merge([
                'total' => num_format($this->basket->subTotal()) . " (" . money('symbol') . ")"
            ], trans('site.cart.msgs.removed'));
        }

        return trans('site.cart.msgs.updated');
    }
};  */  