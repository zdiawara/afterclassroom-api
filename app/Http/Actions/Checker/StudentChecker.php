<?php

namespace App\Http\Actions\Checker;

use App\Classe;
use App\Student;
use App\Http\Actions\Checker\Checker;


class StudentChecker extends Checker{
    
    /**
     * Verifie si l'étudiant peut s'inscrire dans la classe donnée
     */
    public function canSubcribeClasse (Student $student, Classe $classe){
        if($student==null || $classe==null || $classe->position > $student->classe->position){
            $this->updatePrivilegeException("Impossible de vous inscrire dans cette classe");
        }
        return true;
    }
    
}