<?php

namespace App\Rules;

use App\Referentiel;
use App\Constants\TypeReferentiel;
use Illuminate\Contracts\Validation\Rule;

class CheckTrimestre implements Rule
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
    public function passes($attribute, $id)
    {
        if (isset($id)) {
            $referentiel = Referentiel::find($id);
            return isset($referentiel) && $referentiel->type == TypeReferentiel::TRIMESTRE;
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
        return "Le type de contrôle n'est pas prise en charge";
    }
}
