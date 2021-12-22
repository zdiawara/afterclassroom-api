<?php

namespace App\Rules;

use App\CollegeYear;
use App\Referentiel;
use Illuminate\Contracts\Validation\Rule;

class CheckCollegeYear implements Rule
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
    public function passes($attribute, $year)
    {
        return CollegeYear::find($year) != null;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Cette année scolaire n'est pas disponible sur la plateforme";
    }
}
