<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'password','mobile',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function Codes(){
        return $this->hasMany(Code::class,'user_id');
    }

    public function  WishListsProducts(){
        return $this->belongsToMany(Product::class,'wish_lists','user_id')->withTimestamps();
    }

    public function  WishListHas($product_id){
        return self::WishListsProducts()->where('product_id',$product_id)->exists();
    }
}
