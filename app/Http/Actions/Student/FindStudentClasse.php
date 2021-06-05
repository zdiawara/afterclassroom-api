<?php

namespace App\Http\Actions\Student;

use App\Http\Actions\CollegeYear\CollegeYearInProgress;
use App\Student;
use App\StudentClasse;

class FindStudentClasse
{

    private CollegeYearInProgress $collegeYearInProgress;

    public function __construct(CollegeYearInProgress $collegeYearInProgress)
    {
        $this->collegeYearInProgress = $collegeYearInProgress;
    }

    public function current(Student $student)
    {
        return StudentClasse::where('student_id', $student->id)
            ->where('college_year_id', $this->collegeYearInProgress->execute()->id)
            ->firstOrFail()
            ->classe;
    }
}
