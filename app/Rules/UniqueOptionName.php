<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\OptionTranslation;


class UniqueOptionName implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $optionName;
    private $optionId;
    public function __construct( $optionName ,  $optionId )
    {
        $this->optionName = $optionName;
        $this-> $optionId = $optionId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($option, $value)
    {
       $option= OptionTranslation::where('name',$value)->first();
       if($option)
       return false;
       return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'هذا الحقل مستخدم من قبل';
    }
}
