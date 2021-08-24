<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Image;
use App\Models\Tag;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductImgRequest;
use App\Http\Requests\PriceRequest;
use App\Http\Requests\StockRequest;
use DB;
use Illuminate\Support\Str;


class ProductController extends Controller
{
    public function index(){
      $products = Product::Selection()->orderBy('created_at', 'desc')->get();
      return view ('dashboard.products.general.index' , compact('products'));
    }

    public function create(){
        $data = [];
        $data['brands'] = Brand::Active()->select('id')->get();
        $data['tags'] = Tag::select('id')->get();
        $data['categories'] = Category::Active()->select('id')->get();
        return view('dashboard.products.general.create', $data);

    }
    public function store(ProductRequest $request)
    {
      
      try{

        DB::beginTransaction();

        //validation

        if (!$request->has('is_active'))
            $request->request->add(['is_active' => 0]);
        else
            $request->request->add(['is_active' => 1]);

        $product = Product::create([
            'slug' => $request->slug,
            'brand_id' => $request->brand_id,
            'is_active' => $request->is_active,
        ]);
        //save translations
        $product->name = $request->name;
        $product->description = $request->description;
        $product->short_description = $request->short_description;
        $product->save();
        //save product categories

         $product->categories()->sync($request['Categories']); // array of value

        //save product tags
        $product->tags()->attach($request->tags);

        DB::commit();
        return redirect()->route('index.product')->with(['success' => 'تم ألاضافة بنجاح']);
      }catch(\Exception $ex)
      {
        return redirect()->route('index.product')->with(['error' => '  حدث خطأ ما يرجى المحاوله لاحقا']);
      }


    }

    public function getPrice($id){
       $product_id = Product::find($id);
      if(! $product_id)
      return redirect()->route('index.product')->with(['error'=> 'هذا المنتج غير موجود']);
      return view ('dashboard.products.prices.create',compact('product_id'));
    }

    public function postPrice(PriceRequest $request ){
     // return $request;
      try{
        if ($request->special_price_exist==0)  // No Special Price
        $request->request->add([
          'special_price'=> null,
          'special_price_type'=> null,
          'special_price_start'=> null,
          'special_price_end'=> null
        ]);

        Product::where('id',$request->product_id)
        ->update($request
                         ->only(['price','special_price','special_price_type','special_price_type'
                           ,'special_price_start','special_price_end']));
        return redirect()->route('index.product')->with(['success' => 'تم التحديث بنجاح']);   
      }catch(\Exception $ex)
      { return $ex;
        return redirect()->route('index.product')->with(['error' => '  حدث خطأ ما يرجى المحاولة فيما بعد']);   
      }
     
    }

    public function getStock($id){
      // $product_id = Product::select('id')->get();
      $product = Product::find($id);
      if(! $product)
      return redirect()->route('index.product')->with(['error'=> 'هذا المنتج غير موجود']);
       return view ('dashboard.products.stock.create',compact('product'));
     }
 
     public function saveProductStock(StockRequest $request ){
       try{
         if($request->manage_stock==0) //0=>no manage stock
         $request->request->add(['qty'=> null]);
         Product::whereId($request -> product_id) -> update($request -> only(['sku','manage_stock','in_stock','qty']));
         return redirect()->route('index.product')->with(['success' => 'تم التحديث بنجاح']);   
       }catch(\Exception $ex)
       {
         return redirect()->route('index.product')->with(['error' => '  حدث خطأ ما يرجى المحاولة فيما بعد']);   
       }
      
     }

     public function addImages($id){
      $product=Product::orderBy('id' ,'DESC')->find($id);
      if(! $product)
      return redirect()->route('index.product')->with(['error'=> 'هذا المنتج غير موجود']);
       
      return view('dashboard.products.images.create',compact('product'));
     }

     // save images to folder only
     public function saveProductImages(Request $request){
      $file = $request->file('dzfile');
      $filename = uploadImage('products', $file);

      return response()->json([
          'name' => $filename,
          'original_name' => $file->getClientOriginalName(),
      ]);
     }

     // save images to db
     public function saveProductImagesDB(ProductImgRequest $request){
         try{
           // save dropzone images
           if ($request->has('document') && count($request->document) > 0) {
            foreach ($request->document as $image) {
                Image::create([
                    'product_id' => $request->product_id,
                    'photo' => $image,
                ]);
            }
        }
        return redirect()->route('index.product')->with(['success' => 'تم التحديث بنجاح']);

         }catch(\Exception $ex)
         {
          return redirect()->route('index.product')->with(['error'=>' حدث خطأ ما يرجي المحاولة فيما بعد']);
         }
    }


    public function ImageIndex($id){
      $products=Product::find($id);
      if(! $products )
        return redirect()->route('index.product')->with(['error'=> 'هذا المنتج غير موجود']);
        $images = $products->productimages;
        return view('dashboard.products.images.index',compact('images'));
    
 }

 public function delete($id){
  try{
    $image=Image::find($id);
    if (!$image)
    return redirect()->route('index.product')->with(['error' => 'هذا المنتج غير موجود']);
    $img= getImage($image);
    $Image = getImage($image->photo);
    unlink($Image);
    $image->delete();
    return redirect()->route('index.product')->with(['success'=>'تم الحذف بنجاح']);

  }catch(\Exception $ex)
  { 
    return redirect()->route('index.product')->with(['error'=>' حدث خطأ ما يرجي المحاولة فيما بعد']);

  }
}   
}
