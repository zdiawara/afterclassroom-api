<?php

namespace App\Http\Actions\User;

use App\User;
use App\Chapter;
use App\Matiere;
use App\Teacher;
use App\Referentiel;
use App\MatiereTeacher;
use App\Constants\CodeReferentiel;
use App\Constants\TypeReferentiel;
use App\Exceptions\NotFoundException;
use App\Exceptions\PrivilegeException;
use Illuminate\Database\Eloquent\Model;


class UserChecker{

    private $enseignements = [Chapter::class];
    
    private $users = [Teacher::class];

    private function contains($models,$model){
        return collect($models)->contains(function ($value) use ($model) {
            return $model instanceof $value;
        });
    }

    private function isEnseignement(Model $enseignement){
        return $this->contains($this->enseignements,$enseignement);
    }

    private function isUser(Model $user){
        return $this->contains($this->users,$user);
    }

    private function updatePrivilegeException($message = "Vous n'avez pas les droits pour modifier cette ressource."){
        throw new PrivilegeException($message);
    }

    private function deletePrivilegeException($message = "Vous n'avez pas les droits pour supprimer cette ressource."){
        throw new PrivilegeException($message);
    }

    private function notFoundException($message = "Ressource introuvable !"){
        throw new NotFoundException($message);
    }

    /**
     * Verifie si l'utilisateur connecté peut modifier le profil
     */
    public function checkUpdate(Model $model){
        $user = auth()->userOrFail();
        // User
        if($this->isUser($model)){
            if($user->isTeacher() && $model->id==$user->userable_id){
                return true;
            }
        }
        $this->updatePrivilegeException();

    }

    /**
     * Verifie si l'utilisateur connecté peut modifier l'enseignement
     */
    public function checkUpdateEnseignement(Model $model){

        $user = auth()->userOrFail();

        // Enseignement
        if($this->isEnseignement($model)){
            if($user->isTeacher() && $model->teacher_id == $user->userable_id){
                return true;
            }
        }
        $this->updatePrivilegeException();
    }

    /**
     * Verifie si l'enseignant peut enseigner la matière en paramètre
     */
    public function canTeachMatiere(Teacher $teacher, Matiere $matiere){
        if($teacher==null || $matiere==null){
            return false;
        }
        $teacherMatiere = MatiereTeacher::where('teacher_id',$teacher->id)
            ->where('matiere_id',$matiere->id)
            ->with(['etat'])
            ->first();
        
        if(is_null($teacherMatiere)){
            $this->updatePrivilegeException("Vous ne pouvez pas enseigner ".$matiere->name);
        }
        if($teacherMatiere->etat->code != CodeReferentiel::VALIDATED){
            $this->updatePrivilegeException("Impossible d'enseigner une matière qui n'est pas encore validée.");
        }
        return true;
    }

    
    /**
     * Verifie que le teacher peut supprimer une matiere qu'il enseigne
     */
    public function canDeleteTeacherMatiere(Teacher $teacher, Matiere $matiere) {

        $user = auth()->userOrFail();

        $teacherMatiere = MatiereTeacher::where('teacher_id',$teacher->id)
                ->where('matiere_id',$matiere->id)
                ->first();

        if(\is_null($teacherMatiere)){
            $this->notFoundException();
        }
        
        // Enseignement
        if($user->isTeacher() && $user->userable->id == $teacher->id){
            return true;
        }
        
        $this->deletePrivilegeException();
    }


}