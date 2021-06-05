<?php

namespace App\Rules;

use App\Matiere;
use App\Specialite;
use Illuminate\Contracts\Validation\Rule;

class CheckSpecialite implements Rule
{

    public function __construct()
    {
        //
    }

    public function passes($attribute, $id)
    {
        return isset($id) ? Specialite::find($id) != null : true;
    }

    public function message()
    {
        return "La spécialité n'existe pas";
    }
}
