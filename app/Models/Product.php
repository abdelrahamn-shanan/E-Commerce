<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;
class Product extends Model
{
    use Translatable,
    SoftDeletes;

/**
 * The relations to eager load on every query.
 *
 * @var array
 */
protected $with = ['translations'];

/**
 * The attributes that are mass assignable.
 *
 * @var array
 */
protected $guarded = [];

/**
 * The attributes that should be cast to native types.
 *
 * @var array
 */
protected $casts = [
    'manage_stock' => 'boolean',
    'in_stock' => 'boolean',
    'is_active' => 'boolean',
    'special_price_start' => 'string',
    'special_price_end' => 'string',
];

/**
 * The attributes that should be mutated to dates.
 *
 * @var array
 */
protected $dates = [
    'special_price_start',
    'special_price_end',
    'start_date',
    'end_date',
    'deleted_at',
];

/**
 * The accessors to append to the model's array form.
 *
 * @var array
 */
/* protected $appends = [
    'base_image', 'formatted_price', 'rating_percent', 'is_in_stock', 'is_out_of_stock',
    'is_new', 'has_percentage_special_price', 'special_price_percent',
];*/

/**
 * The attributes that are translatable.
 *
 * @var array
 */
protected $translatedAttributes = ['name', 'description', 'short_description'];

public function brand()
{
    return $this->belongsTo(Brand::class)->withDefault(); 
}

public function categories()
{
    return $this->belongsToMany(Category::class, 'product_categories');
}



public function tags()
{
    return $this->belongsToMany(Tag::class, 'product_tags');
}

public function images()
    {
        return $this->hasMany(Image::class)->select('product_id','photo');
    }

public function scopeSelection($query)
{

    return $query->select('id','sku' ,'qty' , 'in_stock' , 'manage_stock','slug'
    , 'is_active','price' , 'created_at' , 'updated_at' , 'special_price_start', 'special_price_end');
}

public function scopeActive($query){
    return $query->where('is_active' , 1);
    }
    
public function  getActive(){

    return   $this -> is_active == 1 ? 'مفعل' : ' غير مفعل';
    }
    
public function options()
    {
        return $this->hasMany(Option::class, 'product_id');
    } 

public function  Users(){
        return $this->belongsToMany(User::class,'wish_lists','product_id');
    }

    public function hasStock($quantity)
    {
        return $this->qty >= $quantity;
    }

    public function outOfStock()
    {
        return $this->qty === 0;
    }

    public function inStock()
    {
        return $this->qty >= 1;
    }

    public function getTotal($converted = true)
    {
        return $total =  $this->special_price ?? $this -> price;

    }

}
