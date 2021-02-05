<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Education implements Rule
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
        $education_attainment = ["ELEMENTARY","COLLEGE","HIGH SCHOOL", "POST GRADUATE", "OTHERS"];
        return in_array($value,$education_attainment) ?  true : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Please Select valid education attainment';
    }
}
