<?php

namespace App\Http\Actions\TeacherMatiere;

use App\ClasseMatiere;

use function Psy\debug;

class FindTeacherMatiere
{

    public function __construct()
    {
    }


    /**
     * 
     */
    public function findPrincipalTeacher(string $matiere, string $classe)
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
