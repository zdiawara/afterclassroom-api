<?php

namespace App\Http\Actions\Subscription;

use App\Classe;
use App\Student;
use App\Constants\CodeReferentiel;
use App\Http\Actions\Checker\StudentChecker;
use App\Http\Actions\Checker\TeacherMatiereChecker;
use App\Http\Actions\CollegeYear\CollegeYearInProgress;
use App\Subscription;

class ManageSubscription
{

    private TeacherMatiereChecker $teacherMatiereChecker;
    private StudentChecker $studentChecker;
    private CollegeYearInProgress $collegeYearInProgress;

    public function __construct(
        TeacherMatiereChecker $teacherMatiereChecker,
        StudentChecker $studentChecker,
        CollegeYearInProgress $collegeYearInProgress
    ) {
        $this->teacherMatiereChecker = $teacherMatiereChecker;
        $this->studentChecker = $studentChecker;
        $this->collegeYearInProgress = $collegeYearInProgress;
    }


    public function create(array $params)
    {
        $student = Student::findOrFail($params['student']);
        $teacher = isset($params['teacher']) ? $params['teacher']  : null;
        $matiere = isset($params['matiere']) ? $params['matiere']  : null;
        $classe = isset($params['classe']) ? $params['classe']  : null;
        $enseignement = isset($params['enseignement']) ? $params['enseignement']  : null;

        $this->studentChecker->canSubcribeClasse($student, Classe::findOrFail($classe));

        if ($enseignement == CodeReferentiel::BASIC) {
            $this->teacherMatiereChecker->canTeach($teacher, $matiere);
        } else {
            $matiere = null;
            $teacher = null;
        }

        $collegeYear = $this->collegeYearInProgress->execute();

        $subscription = Subscription::withTrashed()->where('college_year_id', $collegeYear->id)
            ->where('student_id', $student->id)
            ->where('enseignement_id', $enseignement)
            ->where('classe_id', $classe)
            ->where(function ($query) use ($enseignement, $teacher, $matiere) {
                if ($enseignement === CodeReferentiel::BASIC) {
                    return $query->where('teacher_id', $teacher)
                        ->where('matiere_id', $matiere);
                }
                return $query;
            })
            ->first();

        if (isset($subscription)) {
            $subscription->restore();
        } else {
            $subscription = Subscription::create([
                'college_year_id' => $collegeYear->id,
                'student_id' => $student->id,
                'teacher_id' => $teacher,
                'classe_id' => $classe,
                'matiere_id' => $matiere,
                'enseignement_id' => $enseignement
            ]);
        }
        return $subscription;
    }

    public function delete(array $params)
    {
        $collegeYear = $this->collegeYearInProgress->execute();
        $subscription = Subscription::withTrashed()->where('college_year_id', $collegeYear->id)
            ->where('student_id', $params['student'])
            ->where('enseignement_id', $params['enseignement'])
            ->where('classe_id', $params['classe'])
            ->where(function ($query) use ($params) {
                if ($params['enseignement'] === CodeReferentiel::BASIC) {
                    return $query->where('teacher_id', $params['teacher'])
                        ->where('matiere_id', $params['matiere']);
                }
                return $query;
            })
            ->firstOrFail();

        $subscription->delete();
    }
}
