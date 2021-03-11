<?php

namespace App\Http\Requests;

class EnseignementRules
{

    public static function getRules()
    {
        return [
            'classe' => 'required',
            'matiere' => 'required',
            'teacher' => 'required'
        ];
    }
}
