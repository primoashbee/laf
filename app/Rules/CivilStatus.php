<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CivilStatus implements Rule
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
        $civil_status = ["SINGLE","MARRIED","SEPARATED", "WIDOW",'DIVORCED'];
        return in_array($value,$civil_status) ?  true : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Please Select valid civil status';
    }
}
