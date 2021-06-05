<?php

namespace App\Http\Actions\Subscription;

use App\Subscription;

class ListSubscription
{

    public function execute(string $studentId, string $classeId, string $collegeYearId)
    {
        return Subscription::where('student_id', $studentId)
            ->where('college_year_id', $collegeYearId)
            ->where('classe_id', $classeId)
            ->with(['teacher', 'matiere', 'collegeYear', 'classe', 'enseignement'])
            ->get();
    }
}
