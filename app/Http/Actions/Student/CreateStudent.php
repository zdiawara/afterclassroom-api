<?php

namespace App\Http\Actions\Student;

use App\Classe;
use App\Matiere;
use App\Student;
use App\Teacher;
use App\CollegeYear;
use App\Referentiel;
use App\StudentTeacher;
use App\Constants\CodeReferentiel;
use App\Constants\TypeReferentiel;
use App\Http\Actions\User\ManageUser;
use App\Http\Actions\User\ManageIdentify;
use App\Http\Actions\Checker\StudentChecker;
use App\Http\Actions\Checker\TeacherMatiereChecker;
use App\Http\Actions\TeacherMatiere\FindTeacherMatiere;

class CreateStudent
{

    private ManageUser $manageUser;
    private ManageIdentify $manageIdentify;

    public function __construct(ManageUser $manageUser,  ManageIdentify $manageIdentify)
    {
        $this->manageUser = $manageUser;
        $this->manageIdentify = $manageIdentify;
    }


    public function execute(array $fields)
    {
        $student = new Student($fields);

        $student->save();

        $student->user()->save($user = $this->manageUser->create($request, $username));


        $enseignement = Referentiel::where('code', $params['enseignement'])
            ->where('type', TypeReferentiel::ENSEIGNEMENT)
            ->firstOrFail();

        $teacher = null;
        if ($params['enseignement'] == CodeReferentiel::BASIC) {
            $teacher = Teacher::whereHas('user', function ($q) use ($params) {
                $q->where('username', $params["teacher"]);
            })->firstOrFail();
        } else {
            $teacher = $this->findTeacherMatiere->findPrincipalTeacher($params['matiere'], $params['classe']);
        }


        $matiere = Matiere::where('code', $params['matiere'])->firstOrFail();
        $classe = Classe::where('code', $params['classe'])->firstOrFail();
        $collegeYear = CollegeYear::whereHas('etat', function ($q) {
            $q->where('code', CodeReferentiel::IN_PROGRESS);
        })->firstOrFail();

        $this->teacherMatiereChecker->canEdit($teacher, $matiere);
        $this->studentChecker->canSubcribeClasse($student, $classe);

        $studentTeacher = StudentTeacher::withTrashed()->where('college_year_id', $collegeYear->id)
            ->where('enseignement_id', $enseignement->id)
            ->where('student_id', $student->id)
            ->where('matiere_id', $matiere->id)
            ->where('classe_id', $classe->id)
            ->where('teacher_id', $teacher->id)
            ->first();

        if (isset($studentTeacher)) {
            $studentTeacher->restore();
        } else {
            $studentTeacher = StudentTeacher::create([
                'college_year_id' => $collegeYear->id,
                'student_id' => $student->id,
                'matiere_id' => $matiere->id,
                'classe_id' => $classe->id,
                'teacher_id' => $teacher->id,
                'enseignement_id' => $enseignement->id
            ]);
        }
        return $studentTeacher;
    }
}
