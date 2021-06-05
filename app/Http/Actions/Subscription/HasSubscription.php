<?php

namespace App\Http\Actions\Subscription;

use App\Constants\CodeReferentiel;
use App\Http\Actions\CollegeYear\CollegeYearInProgress;
use App\Subscription;

class HasSubscription
{

    private CollegeYearInProgress $collegeYearInProgress;

    public function __construct(CollegeYearInProgress $collegeYearInProgress)
    {
        $this->collegeYearInProgress = $collegeYearInProgress;
    }


    public function execute(array $params)
    {
        $student = isset($params['student']) ? $params['student']  : null;
        $teacher = isset($params['teacher']) ? $params['teacher']  : null;
        $matiere = isset($params['matiere']) ? $params['matiere']  : null;
        $classe = isset($params['classe']) ? $params['classe']  : null;
        $enseignement = isset($params['enseignement']) ? $params['enseignement']  : null;
        $collegeYear = $this->collegeYearInProgress->execute();

        $subscription = Subscription::where('college_year_id', $collegeYear->id)
            ->where('student_id', $student)
            ->where('enseignement_id', $enseignement)
            ->where('classe_id', $classe)
            ->where(function ($query) use ($enseignement, $teacher, $matiere) {
                if ($enseignement === CodeReferentiel::BASIC) {
                    return $query->where('teacher_id', $teacher)
                        ->where('matiere_id', $matiere);
                }
                return $query;
            })
            ->first();

        return isset($subscription);
    }
}
