<?php

namespace App\Http\Actions\Checker;

use App\Matiere;
use App\Teacher;
use App\TeacherMatiere;
use App\Constants\CodeReferentiel;
use App\Http\Actions\Checker\Checker;


class TeacherMatiereChecker extends Checker
{

    private function checkTeacherMatiere(string $teacherId, string $matiereId, $etats)
    {

        if ($teacherId == null || $matiereId == null) {
            return false;
        }

        $teacherMatiere = TeacherMatiere::where('teacher_id', $teacherId)
            ->where('matiere_id', $matiereId)
            ->first();

        if (is_null($teacherMatiere)) {
            $this->updatePrivilegeException("Cet enseignant n'enseigne pas en " . $matiereId);
        }
        if (!collect($etats)->contains($teacherMatiere->etat_id)) {
            $this->updatePrivilegeException("Impossible de publier sur la plateforme. Votre matière n'est pas validée");
        }
        return true;
    }

    public function canEdit(string $teacherId, string $matiereId)
    {
        return $this->checkTeacherMatiere($teacherId, $matiereId, [CodeReferentiel::VALIDATED, CodeReferentiel::VALIDATING]);
    }

    /**
     * Verifie si l'enseignant peut enseigner la matière en paramètre
     */
    public function canTeach(string $teacherId, string $matiereId, bool $validating = false)
    {
        $etats = [CodeReferentiel::VALIDATED];
        if ($validating) {
            //$etats [] = CodeReferentiel::VALIDATING;
        }
        $this->checkTeacherMatiere($teacherId, $matiereId, $etats);
    }

    /**
     * Verifie que le teacher peut supprimer une matiere qu'il enseigne
     */
    public function canDelete(string $teacherId, string $matiereId)
    {

        $user = auth()->userOrFail();

        $teacherMatiere = TeacherMatiere::where('teacher_id', $teacherId)
            ->where('matiere_id', $matiereId)
            ->first();

        if (is_null($teacherMatiere)) {
            $this->notFoundException();
        }

        // Enseignement
        if ($user->isTeacher() && $user->username == $teacherId) {
            return true;
        }

        $this->deletePrivilegeException();
    }
}
