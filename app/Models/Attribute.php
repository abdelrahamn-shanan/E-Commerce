<?php

namespace App\Models;
use Astrotomic\Translatable\Translatable;
use App\Http\Requests\MainCategoryRequest;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use Translatable;

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['translations'];


    protected $translatedAttributes = ['name'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $Visible = ['translations'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
   
    public function options()
    {
        return $this->hasMany(Option::class, 'attribute_id');
    } 
}
