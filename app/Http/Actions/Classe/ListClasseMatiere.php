<?php

namespace App\Http\Actions\Classe;

use App\Classe;
use App\ClasseMatiere;

class ListClasseMatiere
{

    public function byClasse(Classe $classe)
    {

        return ClasseMatiere::where('classe_id', $classe->id)
            ->with(['matiere.specialites'])
            ->get()
            ->map(function ($classeMatiere) {
                return $classeMatiere->matiere;
            })->all();
    }
}
