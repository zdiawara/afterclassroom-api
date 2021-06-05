<?php

namespace App\Rules;

use App\Referentiel;
use App\Constants\TypeReferentiel;
use Illuminate\Contracts\Validation\Rule;

class CheckEnseignement implements Rule
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
        $referentiel = Referentiel::find($id);
        return isset($referentiel) && $referentiel->type == TypeReferentiel::ENSEIGNEMENT;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Le type d'enseignement n'existe pas";
    }
}
