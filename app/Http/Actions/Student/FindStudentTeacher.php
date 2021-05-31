<?php

namespace App\Http\Actions\Student;

use App\User;
use App\StudentTeacher;

class FindStudentTeacher
{

    public function byId(User $user, array $params)
    {
        return StudentTeacher::where('student_id', $user->username)
            ->where('teacher_id', $params['teacher'])
            ->where("classe_id", $params['classe'])
            ->where("matiere_id", $params['matiere'])
            ->where("enseignement_id", $params['enseignement'])
            ->first();
    }
}
