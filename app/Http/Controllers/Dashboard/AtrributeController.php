<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attribute;
use App\Http\Requests\AttributeRequest;
use DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AtrributeController extends Controller
{
    public function index(){
        $attributes= Attribute::orderBy('id' ,'DESC')->paginate(PAGINATION_COUNT);
        return view('dashboard.attributes.index', compact('attributes'));
   }

   public function create(){
       return view('dashboard.attributes.create');

   }
   public function store(AttributeRequest $request)
   {
            DB::beginTransaction();
           $attribute = Attribute::create([]);
           $attribute->name = $request->name;
           $attribute->save();
           DB::commit();
           return redirect()->route('index.attribute')->with(['success'=>'تم الاضافة بنجاح']);
           DB::rollback();
           return redirect()->route('index.attribute')->with(['error'=>' حدث خطأ ما يرجي المحاولة فيما بعد']);
       }

   
   public function edit($id){
       $attribute=Attribute::find($id);
     if(!$attribute)
         return redirect()->route('index.attribute')->with(['error'=> 'هذا العنصر غير موجود']);

      return view ('dashboard.attributes.edit' ,compact('attribute'));   
     
  }

  public function update($id , AttributeRequest $request){
    try{
      $attribute = Attribute::find($id);
      if (!$attribute)
        return redirect()->route('index.attribute')->with(['error' => 'هذا العنصر غير موجود']);

    DB::beginTransaction();
    $attribute -> update([]);
     // save translation
     $attribute->name = $request->name;
     $attribute->save();
     DB::commit();
      return redirect()->route('index.attribute')->with(['success'=>'تم التحديث بنجاح']);

    }catch(\Exception $ex)
    {
     DB::rollback();
     return redirect()->route('index.attribute')->with(['error'=>' حدث خطأ ما يرجي المحاولة فيما بعد']);
    }
  }

  public function delete($id){
      try{
        $attribute = Attribute::find($id);
        if (!$attribute)
        return redirect()->route('index.attribute')->with(['error' => 'هذا العنصر غير موجود']);
        $attribute->delete();
        return redirect()->route('index.attribute')->with(['success'=>'تم الحذف بنجاح']);

      }catch(\Exception $ex)
      {
        return redirect()->route('index.attribute')->with(['error'=>' حدث خطأ ما يرجي المحاولة فيما بعد']);

      }
  }
}
