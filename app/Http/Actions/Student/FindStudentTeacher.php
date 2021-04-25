<?php

namespace App\Http\Actions\Student;

use App\User;
use App\StudentTeacher;
use Illuminate\Support\Facades\DB;

class FindStudentTeacher
{

    public function byCode(User $user, array $params)
    {
        $teacher = $params['teacher'];
        $matiere = $params['matiere'];
        $classe = $params['classe'];

        return StudentTeacher::where('student_id', $user->userable_id)
            ->whereHas("classe", function ($q) use ($classe) {
                $q->where(DB::raw('lower(classes.code)'), strtolower($classe));
            })
            ->whereHas("matiere", function ($q) use ($matiere) {
                $q->where(DB::raw('lower(matieres.code)'), strtolower($matiere));
            })
            ->whereHas("teacher.user", function ($q) use ($teacher) {
                $q->where(DB::raw('lower(users.username)'), strtolower($teacher));
            })->first();
    }
}
