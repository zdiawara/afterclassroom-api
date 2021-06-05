<?php

namespace App\Http\Requests;

use App\Rules\CheckClasse;
use App\Rules\CheckMatiere;
use App\Rules\CheckTeacher;
use App\Rules\CheckSpecialite;

class EnseignementRules
{

    public static function getRules()
    {
        return [
            'classe' => ['required', new CheckClasse],
            'matiere' => ['required', new CheckMatiere],
            'specialite' => [new CheckSpecialite],
            'teacher' => ['required', new CheckTeacher]
        ];
    }
}
