<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class BusinessType implements Rule
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
        $business_type = ["TRADING / MERCHANDISING","MANUFACTURING","SERVICE", "AGRICULTURE"];
        return in_array($value,$business_type) ?  true : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Please select valid business type';
    }
}
