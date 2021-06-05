<?php

namespace App\Http\Actions\CollegeYear;

use App\CollegeYear;

class CollegeYearInProgress
{

    public function execute()
    {
        return CollegeYear::whereDate('started_at', '<=', date("Y-m-d"))
            ->whereDate('finished_at', '>=', date("Y-m-d"))
            ->firstOrFail();
    }
}
