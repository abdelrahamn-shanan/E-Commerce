<?php

namespace App\Http\Controllers\Site;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\VerificationRequest;
use App\Http\Services\VerificationServices;
use  App\Models\Code; 
use Auth;
use  Carbon\Carbon;



class VerificationCodeController extends Controller
{

    public $verificationcode;
    public function __construct(VerificationServices $verificationservices)
    {
        $this->middleware('auth');
        $this -> verificationcode = $verificationservices;
       }
   
    public function verify(VerificationRequest $request)
    {
      $check =  $this -> verificationcode->checkOtpCode($request->code);
      if (!$check ){
        return redirect()->route('verifypage')->withErrors(['code' =>('الكود الذي ادخلته غير صحيح')]);
    }else{
      $this -> verificationcode->removeotpcode($request-> code);
      return redirect()->route('home');
      }
    }

    public function getverifypage(){
        return view('auth.verification');
    }

    public function resendOtp(Request $request)
    {
        $loggedInUsercode= Code::where('user_id' ,Auth::user()->id)->delete();
        $verification=[];
        $verification['user_id'] = Auth::user()->id;
        $this -> verificationcode->setVerificationCode($verification);
        return redirect()->route('verifypage')->with(['success'=>' تم إعادة الإرسال بنجاح']);
       

    }
}