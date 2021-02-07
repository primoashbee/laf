<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Branch implements Rule
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

        $branches = Office::where('level','branch')->pluck('name');
        return in_array($value,$branches) ?  true : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Branch selected is not included on list.';
    }
}
