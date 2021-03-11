<?php

namespace App\Http\Actions\Checker;

use App\Matiere;
use App\Teacher;
use App\MatiereTeacher;
use App\Constants\CodeReferentiel;
use App\Http\Actions\Checker\Checker;


class TeacherMatiereChecker extends Checker
{

    private function checkTeacherMatiere(Teacher $teacher, Matiere $matiere, $etats)
    {
        if ($teacher == null || $matiere == null) {
            return false;
        }

        $teacherMatiere = MatiereTeacher::where('teacher_id', $teacher->id)
            ->where('matiere_id', $matiere->id)
            ->with(['etat'])
            ->first();

        if (is_null($teacherMatiere)) {
            $this->updatePrivilegeException("Cet enseignant n'enseigne pas en " . $matiere->name);
        }
        if (!collect($etats)->contains($teacherMatiere->etat->code)) {
            $this->updatePrivilegeException("Impossible de publier sur la plateforme. Votre matière n'est pas validée");
        }
        return true;
    }

    public function canEdit(Teacher $teacher, Matiere $matiere)
    {
        $this->checkTeacherMatiere($teacher, $matiere, [CodeReferentiel::VALIDATED, CodeReferentiel::VALIDATING]);
    }

    /**
     * Verifie si l'enseignant peut enseigner la matière en paramètre
     */
    public function canTeach(Teacher $teacher, Matiere $matiere, bool $validating = false)
    {
        $etats = [CodeReferentiel::VALIDATED];
        if ($validating) {
            //$etats [] = CodeReferentiel::VALIDATING;
        }
        $this->checkTeacherMatiere($teacher, $matiere, $etats);
    }

    /**
     * Verifie que le teacher peut supprimer une matiere qu'il enseigne
     */
    public function canDelete(Teacher $teacher, Matiere $matiere)
    {

        $user = auth()->userOrFail();

        $teacherMatiere = MatiereTeacher::where('teacher_id', $teacher->id)
            ->where('matiere_id', $matiere->id)
            ->first();

        if (\is_null($teacherMatiere)) {
            $this->notFoundException();
        }

        // Enseignement
        if ($user->isTeacher() && $user->userable->id == $teacher->id) {
            return true;
        }

        $this->deletePrivilegeException();
    }
}
