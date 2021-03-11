<?php

namespace App\Http\Controllers\Api;

use App\Classe;
use App\Matiere;
use App\Student;
use App\Teacher;
use App\CollegeYear;
use App\StudentTeacher;
use Illuminate\Http\Request;
use App\Constants\CodeReferentiel;
use App\Http\Controllers\Controller;
use App\Http\Actions\Checker\StudentChecker;
use App\Http\Requests\StudentTeacherRequest;
use App\Http\Resources\StudentTeacherResource;
use App\Http\Actions\Student\ListStudentTeacher;
use App\Http\Actions\Checker\TeacherMatiereChecker;

class StudentTeacherController extends Controller
{
    private $teacherMatiereChecker;
    private $studentChecker;
    public function __construct(TeacherMatiereChecker $teacherMatiereChecker, StudentChecker $studentChecker)
    {
        $this->middleware('auth:api');
        $this->middleware('role:student');
        $this->teacherMatiereChecker = $teacherMatiereChecker;
        $this->studentChecker = $studentChecker;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Student $student,Request $request, ListStudentTeacher $listStudentTeacher)
    {
        //
        return StudentTeacherResource::collection($listStudentTeacher->execute($student,$request->get('classe')));
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StudentTeacherRequest $request, Student $student)
    {
        //
        $teacher = Teacher::whereHas('user',function($q) use ($request){
            $q->where('username',$request->get("teacher"));
        })->firstOrFail();

        $matiere = Matiere::where('code',$request->get('matiere'))->firstOrFail();
        $classe = Classe::where('code',$request->get('classe'))->firstOrFail();

        $this->teacherMatiereChecker->canEdit($teacher,$matiere);
        $this->studentChecker->canSubcribeClasse($student,$classe);
        
        $collegeYear = CollegeYear::whereHas('etat',function($q){
            $q->where('code',CodeReferentiel::IN_PROGRESS);
        })->firstOrFail();

        
        $query = StudentTeacher::withTrashed()->where('college_year_id',$collegeYear->id)
        ->where('student_id' , $student->id)
        ->where('matiere_id' , $matiere->id)
        //->where('teacher_id' , $teacher->id)
        ->where('classe_id' , $classe->id);

        // Supprime les relations existantes
        //$query->delete();

        $studentTeacher = $query->where('teacher_id' , $teacher->id)->first();

        if(isset($studentTeacher)){
            $studentTeacher->restore();
        }else{
            $studentTeacher = StudentTeacher::create([
                'college_year_id' => $collegeYear->id,
                'student_id' => $student->id,
                'matiere_id' => $matiere->id,
                'classe_id' => $classe->id,
                'teacher_id' => $teacher->id
            ]);
        }

        $studentTeacher->load(['teacher','collegeYear','matiere','classe']);

        return $this->createdResponse(new StudentTeacherResource($studentTeacher));
    }

    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student,Teacher $teacher, Request $request)
    {
        //
        $matiere = $request->get('matiere');
        $classe = $request->get('classe');
        $year = $request->get('year');
        
        $studentTeacher = StudentTeacher::where('teacher_id',$teacher->id)
        ->where('student_id',$student->id)
        ->whereHas('matiere' , function($q) use ($matiere){
            $q->where('code',$matiere);
         })
         ->whereHas('classe' , function($q) use ($classe){
            $q->where('code',$classe);
         })
        //->whereHas('collegeYear' , function($q) use ($year){
           // $q->where('year',$year);
        //})
        ->first();

        if(!isset($studentTeacher)){
            return $this->deletedResponse();
            //return $this->conflictResponse("Suppression impossible");
        }
        $studentTeacher->delete();
        return $this->deletedResponse();
    }
}
