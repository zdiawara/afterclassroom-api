<?php

namespace App\Http\Controllers\Api;

use App\Student;
use App\Http\Controllers\Controller;
use App\Http\Actions\Checker\UserChecker;
use App\Http\Actions\CollegeYear\CollegeYearInProgress;
use App\Http\Requests\StudentClasseRequest;
use App\Http\Resources\StudentClasseResource;
use App\StudentClasse;

class StudentClasseController extends Controller
{
    public function __construct(UserChecker $userChecker)
    {
        $this->middleware('auth:api');
        $this->middleware('role:student');
        $this->userChecker = $userChecker;
    }

    public function index(Student $student)
    {
        return StudentClasseResource::collection(
            StudentClasse::where('student_id', $student->id)
                ->with(['classe', 'collegeYear'])
                ->get()
        );
    }


    public function store(
        Student $student,
        StudentClasseRequest $request,
        CollegeYearInProgress $collegeYearInProgress
    ) {

        $collegeYear = $collegeYearInProgress->execute();
        $classe = $request->get("classe");
        $studentClasse = StudentClasse::where('student_id', $student->id)
            ->where('college_year_id', $collegeYear->id)
            ->first();

        if (isset($studentClasse)) {
            if ($studentClasse->changed >= 2) {
                return $this->conflictResponse(
                    "Vous avez déjà changer la classe durant l'année scolaire " . $collegeYear->name
                );
            }
            if ($studentClasse->classe_id != $classe) {
                $studentClasse->update([
                    'changed' => $studentClasse->changed + 1,
                    'classe_id' => $classe
                ]);
            }
        } else {
            $studentClasse = StudentClasse::create([
                'classe_id' => $classe,
                'student_id' => $student->id,
                'college_year_id' => $collegeYear->id,
            ]);
        }
        $studentClasse->load(['classe', 'collegeYear']);
        return new StudentClasseResource($studentClasse);
    }

    public function show($id)
    {
        //
    }
}
