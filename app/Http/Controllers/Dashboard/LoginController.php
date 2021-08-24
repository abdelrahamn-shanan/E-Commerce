<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AdminLoginRequest;
use Auth;

class LoginController extends Controller
{
    public function login(){
        return view ('dashboard.auth.login');
    }

    public function postlogin(AdminLoginRequest $request){
     
       
        $remember_me = $request->has('remember_me') ? 'true' : 'false';          
        if(auth()->guard('admin')->attempt(['email'=> $request->input('email') , 'password'=> $request->input('password')],$remember_me))
        {
           // notify()->success('تم الدخول بنجاح');
            return redirect()-> route('admin.dashboard')->with(['success' => __('admin/SuccessMsg.successlogin')]);
        }
         // notify()->error('خطا في البيانات  برجاء المجاولة مجدا ');
        return redirect()->back()->with(['error' => __('admin/SuccessMsg.errorlogin')]);
    }
}
