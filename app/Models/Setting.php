<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Astrotomic\Translatable\Translatable;

class Setting extends Authenticatable
{
    use Translatable; //trait

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['translations']; // return with translationss
       /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = ['key', 'is_translatable', 'plain_value'];

    protected $translatedAttributes = ['value'];

    protected $casts = [
        'is_translatable' => 'boolean', // return true or false
    ];

    public static function setMany($settings)
    {
        foreach($settings as $key=>$value)
        {
            self::set($key, $value);
        }
    }
    
    /**
     * Set the given setting.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
                              //  defaultlocal , ar

    public static function set($key, $value)
    {
        if ($key === 'translatable') {
            return static::setTranslatableSettings($value);
        }

        if(is_array($value))
        {
            $value = json_encode($value);
        }

        static::updateOrCreate(['key' => $key], ['plain_value' => $value]);
    }

       /**
     * Set a translatable settings.
     *
     * @param array $settings
     * @return void
     */
    public static function setTranslatableSettings($settings = [])
    {
        foreach ($settings as $key => $value) {
            static::updateOrCreate(['key' => $key], [
                'is_translatable' => true,
                'value' => $value,
            ]);
        }
    }
}
