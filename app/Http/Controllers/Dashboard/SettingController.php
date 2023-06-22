<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Http\Requests\shippingsRequest;
use DB;

class SettingController extends Controller
{
    public function editShippingMethods($type){

        if($type ==='free')
        $shippingMethod = Setting::where('key', 'free_shipping_label')->first();
        
        elseif($type ==='inner')
         $shippingMethod =  Setting::where('key', 'local_label')->first();
        
        elseif($type ==='outer')
          $shippingMethod =  Setting::where('key', 'outer_label')->first();
    
        else
          return redirect()->route('admin.dashboard')->with(['error' => 'وسيلة توصيل غير صحيحه ']);

        return  view('dashboard.settings.shippings.edit' , compact('shippingMethod')) ;
    }

    public function updateShippingMethods(shippingsRequest $request , $id){
      
            try{
                 $shippingMethod = Setting::find($id);
                DB::beginTransaction();
                $shippingMethod->update(['plain_value' => $request->plain_value,]);
               //$shippingMethod->plain_value = $request->plain_value;
                $shippingMethod->value = $request->value; //save translation
                $shippingMethod->save();
                DB::commit();
                return redirect()->back()->with(['success' => ' تم التحديث بنجاح']);

            }catch(Exception $ex)
            {
            return redirect()->back()->with(['error' => 'هناك خطا ما يرجي المحاولة فيما بعد']);
            DB::rollback();
            }
  }
}