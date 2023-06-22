<?php

namespace App\Http\Services;

use App\Models\Code;
use App\Models\User;
use Auth;
use  Carbon\Carbon;

class VerificationServices
{
    public function setVerificationCode($data)
    {

        $code = mt_rand(100000, 999999);
        $data['code'] = $code;
        $data['otp_expired_at'] = Carbon::now()->addSeconds(10);
        Code::whereNotNull('user_id')->where(['user_id' => $data['user_id']])->delete();
        return Code::create($data);
    }


    public function getSmsVerifyMessageByAppName($code)
    {
        $message = " is your verification code for your account";
        return $code . $message;
    }

    public function checkOtpCode($code)
    {

        if (Auth::guard()->check()) {
            $loggeduser = Code::where('user_id', Auth::id())->first();
            if ($loggeduser->code == $code) {
                User::whereId(Auth::id())->update(['email_verified_at' => now()]);
                return true;
            } else {
                return false;
            }
            // return redirect(RouteServiceProvider::Home);
        }
        return false;
    }

    public function removeotpcode($code)
    {
        Code::where('code', $code)->delete();
    }
}
