<?php

namespace App\Rules;

use App\Referentiel;
use App\Constants\TypeReferentiel;
use Illuminate\Contracts\Validation\Rule;

class CheckTypeExercise implements Rule
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
     * @param  mixed  $id
     * @return bool
     */
    public function passes($attribute, $code)
    {        
        $referentiel = Referentiel::where("code",$code)->where("type",TypeReferentiel::EXERCISE)->first();
        return isset($referentiel);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Le type d'exercise est incorrect";
    }
}
