<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Http\Requests\SliderImgRequest;


class SliderController extends Controller
{
    public function addImages(){
     
       $images = Slider::get(['image']);
       return view('dashboard.sliders.images.create',compact('images'));
       }
  
       // save images to folder only
       public function saveSliderImages(Request $request){
        $file = $request->file('dzfile');
        $filename = uploadImage('sliders', $file);
  
        return response()->json([
            'name' => $filename,
            'original_name' => $file->getClientOriginalName(),
        ]);
       }
  
       // save images to db
       public function saveSliderImagesDB(SliderImgRequest $request){
           try{
             // save dropzone images
             if ($request->has('document') && count($request->document) > 0) {
              foreach ($request->document as $image) {
                  Slider::create([
                      'image' => $image,
                  ]);
              }
          }
          return redirect()->route('admin.dashboard')->with(['success' => 'تم التحديث بنجاح']);
  
           }catch(\Exception $ex)
           {
            return redirect()->route('admin.dashboard')->with(['error'=>' حدث خطأ ما يرجي المحاولة فيما بعد']);
           }
      }
  
}
