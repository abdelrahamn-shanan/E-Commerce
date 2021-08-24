<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\AttributeTranslation;

class UniqueAttributeName implements Rule
{
    private $AttributeName;
    private $AttributeId;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($AttributeName,$AttributeId)
    {
        $this->AttributeName= $AttributeName;

        $this->AttributeId= $AttributeId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if($this->AttributeId)
        // edit form
        $attribute=AttributeTranslation::where('name',$value)
        ->where('attribute_id','!=',$this->AttributeId)->first();
        else // creation form
       $attribute=AttributeTranslation::where('name',$value)->first();

        if ($attribute)
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
        return ' هذا الحقل مستخدم من قبل ';
    }
}
