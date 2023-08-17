<?php

namespace App\Rules\Register;

use Illuminate\Contracts\Validation\Rule;

class CatClient implements Rule
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
        if(in_array($attribute, ["cin", "name"])){
            if(request()->input('categorie') == 2){
                if(strlen(trim(request()->input($attribute)))==0){
                    return false;
                }
            }
        }

        if(in_array($attribute, ["raisonsociale", "rc", "ice"])){
            if(request()->input('categorie') == 1){
                if(strlen(trim(request()->input($attribute)))==0){
                    return false;
                }
            }
        }


        

        return true;
        
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Le champ est obligatoire";

    }
}