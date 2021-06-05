<?php

namespace App\Http\Actions\Student;

use App\Student;
use App\Http\Actions\Subscription\ListSubscription;
use App\Http\Actions\CollegeYear\CollegeYearInProgress;
use App\StudentClasse;

class StudentDetail
{

    private CollegeYearInProgress $collegeYearInProgress;
    private ListSubscription $listSubscription;
    public function __construct(
        CollegeYearInProgress $collegeYearInProgress,
        ListSubscription $listSubscription
    ) {
        $this->collegeYearInProgress = $collegeYearInProgress;
        $this->listSubscription = $listSubscription;
    }

    public function execute(Student $student)
    {
        $collegeYearId = $this->collegeYearInProgress->execute()->id;

        $studentClasse =  StudentClasse::where('student_id', $student->id)
            ->where('college_year_id', $collegeYearId)
            ->with(['classe', 'collegeYear'])
            ->first();

        $student['studentClasse'] = $studentClasse;

        if (isset($studentClasse)) {
            $student['subscriptions'] = $this->listSubscription->execute(
                $student->id,
                $studentClasse->classe_id,
                $collegeYearId
            );
        }
        return $student;
    }
}
