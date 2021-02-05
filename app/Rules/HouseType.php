<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class HouseType implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $houseType = ["RENTED","OWNED"];
        return in_array($value,$houseType) ?  true : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Please select valid house type.';
    }
}
