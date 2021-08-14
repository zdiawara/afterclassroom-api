<?php

namespace App\Http\Controllers\Api;

use DateTime;
use DateInterval;
use App\CollegeYear;
use App\Http\Controllers\Controller;
use App\Http\Requests\CollegeYearRequest;
use App\Http\Resources\CollegeYearResource;

class CollegeYearController extends Controller
{
    public function index()
    {
        return CollegeYearResource::collection(CollegeYear::all());
    }

    public function store(CollegeYearRequest $collegeYearRequest)
    {

        $year = $collegeYearRequest->get("year");
        $collegeYear = CollegeYear::find($year);
        if (isset($collegeYear)) {
            $this->conflictResponse("Cette année scolaire existe déjà !");
        }

        $lastYear = intval($year) - 1;

        $started = new DateTime($lastYear . '-08-01');

        $newCollegeYear = CollegeYear::create([
            'name' => $lastYear . '-' . $year,
            'id' => $year,
            'started_at' => date('Y-m-d H:m:s', $started->getTimestamp()),
            'finished_at' => date('Y-m-d H:m:s', $started->add(new DateInterval('P1Y'))->getTimestamp())
        ]);
        return new CollegeYearResource($newCollegeYear);
    }
}
