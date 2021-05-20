<?php

namespace App\Http\Actions\Student;

use App\Student;
use App\StudentTeacher;
use App\Constants\CodeReferentiel;


class ListStudentTeacher
{

    public function execute(Student $student, $classe = null)
    {

        $query = StudentTeacher::where('student_id', $student->id)
            ->whereHas('collegeYear.etat', function ($q) {
                $q->where('code', CodeReferentiel::IN_PROGRESS);
            });

        if (isset($classe)) {
            $query = $query->whereHas("classe", function ($q) use ($classe) {
                $q->where('code', $classe);
            });
        } else {
            //$query = $query->where('classe_id',$student->classe_id);
        }

        return $query->with(['teacher', 'matiere', 'collegeYear', 'classe', 'enseignement'])->get();
    }
}
