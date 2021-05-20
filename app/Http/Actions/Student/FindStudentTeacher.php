<?php

namespace App\Http\Actions\Student;

use App\User;
use App\StudentTeacher;
use Illuminate\Support\Facades\DB;

class FindStudentTeacher
{

    public function byCode(User $user, array $params)
    {
        return StudentTeacher::where('student_id', $user->userable_id)
            ->whereHas("teacher.user", function ($q) use ($params) {
                $q->where(DB::raw('lower(users.username)'), strtolower($params['teacher']));
            })
            ->whereHas("classe", function ($q) use ($params) {
                $q->where(DB::raw('lower(classes.code)'), strtolower($params['classe']));
            })
            ->whereHas("matiere", function ($q) use ($params) {
                $q->where(DB::raw('lower(matieres.code)'), strtolower($params['matiere']));
            })
            ->whereHas("enseignement", function ($q) use ($params) {
                $q->where(DB::raw('lower(referentiels.code)'), strtolower($params['enseignement']));
            })
            ->first();
    }
}
