<?php

namespace App\Http\Actions\Exercise;

use App\Chapter;
use App\Controle;
use App\Exercise;
use App\Solution;
use App\Constants\CodeReferentiel;
use App\Exceptions\BadRequestException;
use Illuminate\Database\Eloquent\Model;
use App\Http\Actions\Content\ManageContent;
use App\Http\Actions\Checker\EnseignementChecker;
use App\Http\Actions\Referentiel\FindReferentiel;
use App\Http\Actions\Checker\TeacherMatiereChecker;


class CreateExercise {
    
    private $enseignementChecker;
    private $teacherMatiereChecker;
    private $manageContent;
    private $findReferentiel;

    public function __construct(FindReferentiel $findReferentiel,ManageContent $manageContent,EnseignementChecker $enseignementChecker, TeacherMatiereChecker $teacherMatiereChecker){
        $this->enseignementChecker = $enseignementChecker;
        $this->teacherMatiereChecker = $teacherMatiereChecker;
        $this->manageContent = $manageContent;
        $this->findReferentiel = $findReferentiel;
    }

    
    public function execute(Exercise $exercise, Model $model){

        
        $this->enseignementChecker->canCreate($model);

        $this->teacherMatiereChecker->canEdit($model->teacher,$model->matiere);
        
        if(!isset($model->id)){
            $model->save();
        }

        if(!isset($exercise->type_id)){
            $exercise->type_id = $this->findReferentiel(CodeReferentiel::EXERCISE);
        }
        
        $model->exercises()->save($exercise);
        
        $exercise->load('type');
        
        return $exercise;
    }
    
    


}