<?php

define('PAGINATION_COUNT', 10);
 function getFolder()
 {
     return app()->getlocale() === 'ar' ? 'css-rtl' : 'css';
 }

function uploadImage($folder, $image)
{
    $image->store('/', $folder);
    $filename = $image->hashName();
    $path = 'images/'.$folder.'/'.$filename;

    return $path;
}

 function getImage($image)
 {
     $img = Str::after($image, 'assets/');

     return  $imagpath = base_path('public\tenancy/assets/'.$img);
 }

  

//------------------------------------------------------------------------------
/*
 * Handle Endpoint Errors Function 
 */

function handleError($response) {

    $json = json_decode($response);
    if (isset($json->IsSuccess) && $json->IsSuccess == true) {
        return null;
    }

    //Check for the errors
    if (isset($json->ValidationErrors) || isset($json->FieldsErrors)) {
        $errorsObj = isset($json->ValidationErrors) ? $json->ValidationErrors : $json->FieldsErrors;
        $blogDatas = array_column($errorsObj, 'Error', 'Name');

        $error = implode(', ', array_map(function ($k, $v) {
                    return "$k: $v";
                }, array_keys($blogDatas), array_values($blogDatas)));
    } else if (isset($json->Data->ErrorMessage)) {
        $error = $json->Data->ErrorMessage;
    }

    if (empty($error)) {
        $error = (isset($json->Message)) ? $json->Message : (!empty($response) ? $response : 'API key or API URL is not correct');
    }

    return $error;
}