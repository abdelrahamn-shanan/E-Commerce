<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Enumerations\OptionStatus;
use App\Models\Option;
use App\Models\Product;
use App\Models\Attribute;
use App\Http\Requests\OptionRequest;
use DB;


class OptionController extends Controller
{
        public function index(){
          $options = Option::with(['products'=>function($proud)
          {
            $proud ->select('id');
          } ,'attributes' => function ($attribute)
         {
          $attribute-> select('id');
         }])->select('id','product_id','attribute_id' ,'price' , 'is_active')->get();
          return view ('dashboard.options.index' , compact('options'));
        }
    
        public function create(){
            $data = [];
            $data['products'] =Product::Active()->select('id')->get();
            $data['attributes'] =Attribute::select('id')->get();
            return view('dashboard.options.create', $data);
    
        }
        public function store(OptionRequest $request)
        {   
          try{
    
            DB::beginTransaction();
    
            //validation
    
            $option = Option::create([
                'attribute_id' => $request->attribute_id,
                'product_id' => $request->product_id,
                'price' => $request->price,
                'is_active'=> $request->is_active
            ]);
            //save translations
            $option->name = $request->name;
            $option->save();
            DB::commit();
            return redirect()->route('index.option')->with(['success' => 'تم ألاضافة بنجاح']);
          }catch(\Exception $ex)
          {
            return redirect()->route('index.option')->with(['error' => '  حدث خطأ ما يرجى المحاوله لاحقا']);
          }
        }

    public function edit($id){
          $data=[];
          $data['option']= Option::find($id);
          if (! $data['option']) {
            return redirect()->route('index.option')->with(['error' => ('هذا العنصر غير موجود')]);
           }

          $data['products'] =Product::Active()->select('id')->get();
          $data['attributes'] =Attribute::select('id')->get();

           return view('dashboard.options.edit', $data);
    }
    
    public function update($id,OptionRequest $request ){
      try {
        $option = Option::find($id);
        if (!$option) {
            return redirect()->route('index.option')->with(['error' => ('هذا العنصر غير موجود')]);
        }
        $option->update($request->only(['price','product_id','attribue_id']));
        // save translation
        $option->name = $request->name;
        $option->save();

        return redirect()->route('index.option')->with(['success' => __('admin/SuccessMsg.success update')]);
    } catch (\Exception $ex) {
        return redirect()->route('index.option')->with(['error' => __('admin/SuccessMsg.error update')]);
    }
    }
     public function changeStatus($id){
      try{
         $option=Option::find($id);
        if (! $option) {
          return redirect()->route('index.option')->with(['error' => ('هذا العنصر غير موجود')]);
         }

        if($option -> is_active == OptionStatus::NotActive) // غير مفعل
         $option->update([ 'is_active'=> OptionStatus::Active]); // مفعل
         else
         $option->update([ 'is_active'=> OptionStatus::NotActive]); // غير مفعل

         return redirect()->route('index.option')->with(['success' =>'تم تغيير الحالة بنجاح']);

      }catch(\Exception $ex)
      { 
            return redirect()->route('index.option')->with(['error' => __('admin/SuccessMsg.error update')]);
      }
    }   
    }