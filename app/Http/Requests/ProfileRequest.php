<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'=>'required|string',
            'email'=> 'required|email|unique:admins,email,'.$this->id,
            'password'=>'required|confirmed|min:6',
        ];
    }

    public function messages()
    {
        return [
           'required'=>'هذا الحقل مطلوب ',
           'email.email'=>'صيغة البريد الالكترونى غير صحيحة',
           'password.min'=>'الرقم السري قصير جدا  ',
           'password.confirmed'=>'الرقم السري غير متطابق',
        ];
    }
}
