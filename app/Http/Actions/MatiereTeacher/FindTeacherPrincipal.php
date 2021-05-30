<?php

namespace App\Http\Actions\MatiereTeacher;

use App\ClasseMatiere;

class FindTeacherPrincipal
{
    /**
     * 
     */
    public function execute(string $matiere, string $classe)
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
