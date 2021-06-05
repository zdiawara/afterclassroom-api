<?php

namespace App\Http\Actions\Checker;

use App\Classe;
use App\Student;
use App\Http\Actions\Checker\Checker;
use App\Http\Actions\Student\FindStudentClasse;

class StudentChecker extends Checker
{

    private FindStudentClasse $findStudentClasse;

    public function __construct(FindStudentClasse $findStudentClasse)
    {
        $this->findStudentClasse = $findStudentClasse;
    }

    /**
     * Verifie si l'étudiant peut s'inscrire dans la classe donnée
     */
    public function canSubcribeClasse(Student $student, Classe $classe)
    {
        $studentClasse = $this->findStudentClasse->current($student);

        if ($student == null || $classe == null || $classe->position > $studentClasse->position) {
            $this->updatePrivilegeException("Impossible de vous inscrire dans cette classe");
        }
        return true;
    }
}
