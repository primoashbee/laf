<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class IDList implements Rule
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
        $list = ['School ID','PASSPORT','PRC ID','GSIS','SSS','UMID','DRIVERS LICENSE','PHILHEALTH','TIN','VOTERS ID','DIGITIZED POSTAL ID','SOCIAL AMELIORATION PROGRAM','SENIOR CITIZENS ID','EMPLOYEES ID','PAGIBIG MEMBER ID','SOLO PARENT ID','DSWD ID','4PS ID','BARANGAY ID','AFP DEPENDEDNT ID'];
        return in_array($value,$list) ? true : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid ID Type';
    }
}
