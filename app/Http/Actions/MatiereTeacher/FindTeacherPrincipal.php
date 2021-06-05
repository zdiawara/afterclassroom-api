<?php

namespace App\Http\Actions\MatiereTeacher;

use App\ClasseMatiere;

class FindTeacherPrincipal
{
    /**
     * 
     */
    public function execute(string $matiereId, string $classeId)
    {
        $teacherMatiere = ClasseMatiere::where('matiere_id', $matiereId)
            ->where('classe_id', $classeId)
            ->first();

        if (isset($teacherMatiere)) {
            return $teacherMatiere->teacher;
        }
        return null;
    }
}
