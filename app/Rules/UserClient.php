<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UserClient implements Rule
{
    public $message;
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
        if(request()->input('type') == 1){
            return true;
        }
        else{
            return is_numeric(request()->input('client'));
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Le client est obligatoire";

    }
}