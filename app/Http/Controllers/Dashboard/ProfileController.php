<?php

namespace App\Http\Controllers\Dashboard;
use App\Models\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\PasswordRequest;
use DB;
use Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

    public function GetCurrentPassword(){
      return view ('dashboard.profile.password_verify');
    }

    public function Verify(Request  $request){
      $admin = Admin::find(auth('admin')->user()->id) ;
      if (Hash::check($request['old_password'], $admin['password'])) 
        return view ('dashboard.profile.edit',compact('admin'));  
        return redirect()->back()->with(['error' => ' الرقم السري غير صحيح ']); 
    }
    public function update(ProfileRequest $request ){
       try{  
        $admin= Admin::findorfail(auth('admin')->user()->id);
        DB::beginTransaction();
        if($request->filled('password'))
        {
          $request->merge(['password'=>bcrypt($request->password)]);
        }
        unset ($request['id']);
        unset ($request['password_confirmation']);
        $admin->update($request->all());
          $admin->save();
          DB::commit();
          return redirect()->route("admin.dashboard")->with(['success' => ' تم التحديث بنجاح']);
        }catch(\Exception $ex)
        {
            return redirect()->route("admin.dashboard")->with(['error' => ' حدث خطا ما يرجي المحاولة لاحقا ']);
            DB:rollback();
        }
    }
}
