<?php

namespace App\Rules;

use App\User;
use Illuminate\Contracts\Validation\Rule;

class AccountEnabled implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $message;
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
        if (User::where('email', $value)->count() > 0) {
            $this->message = 'Your account is disabled. Please contact system administrator';
            return User::where('email', $value)->first()->disabled ? false : true;
        }
        $this->message = 'Invalid email address and password';
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
