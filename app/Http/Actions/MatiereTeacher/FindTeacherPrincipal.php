<?php

namespace App\Http\Actions\TeacherMatiere;

use App\ClasseMatiere;

class FindTeacherPrincipal
{
    /**
     * 
     */
    public function execute(string $matiere, string $classe)
    {
        $teacherMatiere = ClasseMatiere::whereHas('matiere', function ($q) use ($matiere) {
            $q->where('code', $matiere);
        })
            ->whereHas('classe', function ($q) use ($classe) {
                $q->where('code', $classe);
            })
            ->first();

        if (isset($teacherMatiere)) {
            return $teacherMatiere->teacher;
        }
        return null;
    }
}
