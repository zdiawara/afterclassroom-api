<?php

namespace App\Rules;

use App\Matiere;
use Illuminate\Contracts\Validation\Rule;

class CheckMatiere implements Rule
{

    public function __construct()
    {
        //
    }

    public function passes($attribute, $id)
    {
        return isset($id) ? Matiere::find($id) != null : true;
    }

    public function message()
    {
        return "La matiere n'existe pas";
    }
}
