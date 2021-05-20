<?php

namespace App\Http\Controllers\Api;

use App\Student;
use App\Teacher;
use App\StudentTeacher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StudentTeacherRequest;
use App\Http\Resources\StudentTeacherResource;
use App\Http\Actions\Student\ListStudentTeacher;
use App\Http\Actions\Student\ManageStudentTeacher;

class StudentTeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('role:student');
    }

    public function index(Student $student, Request $request, ListStudentTeacher $listStudentTeacher)
    {
        //
        return StudentTeacherResource::collection($listStudentTeacher->execute($student, $request->get('classe')));
    }

    public function store(Student $student, StudentTeacherRequest $request, ManageStudentTeacher $manageStudentTeacher)
    {
        $params = $request->only(['teacher', 'matiere', 'classe', 'enseignement']);
        $manageStudentTeacher->execute($student, $params);

        $studentTeacher = $manageStudentTeacher->execute($student, $params);
        $studentTeacher->load(['teacher', 'collegeYear', 'matiere', 'classe', 'enseignement']);
        return $this->createdResponse(new StudentTeacherResource($studentTeacher));
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student, Teacher $teacher, Request $request)
    {
        //
        $matiere = $request->get('matiere');
        $classe = $request->get('classe');
        $year = $request->get('year');

        $studentTeacher = StudentTeacher::where('teacher_id', $teacher->id)
            ->where('student_id', $student->id)
            ->whereHas('matiere', function ($q) use ($matiere) {
                $q->where('code', $matiere);
            })
            ->whereHas('classe', function ($q) use ($classe) {
                $q->where('code', $classe);
            })
            //->whereHas('collegeYear' , function($q) use ($year){
            // $q->where('year',$year);
            //})
            ->first();

        if (!isset($studentTeacher)) {
            return $this->deletedResponse();
            //return $this->conflictResponse("Suppression impossible");
        }
        $studentTeacher->delete();
        return $this->deletedResponse();
    }
}
