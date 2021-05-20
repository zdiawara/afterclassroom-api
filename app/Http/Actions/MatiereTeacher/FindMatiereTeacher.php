<?php

namespace App\Http\Actions\MatiereTeacher;

use App\ClasseMatiere;

use function Psy\debug;

class FindMatiereTeacher
{

    public function __construct()
    {
    }


    /**
     * 
     */
    public function findPrincipalTeacher(string $matiere, string $classe)
    {
        $matiereTeacher = ClasseMatiere::whereHas('matiere', function ($q) use ($matiere) {
            $q->where('code', $matiere);
        })
            ->whereHas('classe', function ($q) use ($classe) {
                $q->where('code', $classe);
            })
            ->first();

        if (isset($matiereTeacher)) {
            return $matiereTeacher->teacher;
        }
        return null;
    }
}
