<?php

namespace App\Http\Controllers\Api;

use App\CollegeYear;
use App\Http\Controllers\Controller;
use App\Http\Resources\CollegeYearResource;

class CollegeYearController extends Controller
{
    public function index()
    {
        return CollegeYearResource::collection(CollegeYear::all());
    }
}
