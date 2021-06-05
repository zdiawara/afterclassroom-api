<?php

namespace App\Http\Actions\Classe;

use App\Classe;
use App\ClasseMatiere;

class ListClasseMatiere
{

    public function byClasse(string $classeId)
    {

        return ClasseMatiere::where('classe_id', $classeId)
            ->with(['matiere.specialites'])
            ->get()
            ->map(function ($classeMatiere) {
                return $classeMatiere->matiere;
            })->all();
    }
}
