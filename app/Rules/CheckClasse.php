<?php

namespace App\Rules;

use App\Classe;
use App\Matiere;
use Illuminate\Contracts\Validation\Rule;

class CheckClasse implements Rule
{

    public function __construct()
    {
        //
    }

    public function passes($attribute, $id)
    {
        return Classe::find($id) != null;
    }

    public function message()
    {
        return "La classe n'existe pas";
    }
}
