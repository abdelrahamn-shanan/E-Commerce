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

     return  $imagpath = base_path('public\assets/'.$img);
 }
