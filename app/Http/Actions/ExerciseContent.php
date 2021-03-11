<?php

namespace App\Http\Actions;

use App\Exercise;
use App\Http\Actions\Checker\UserChecker;
use App\Http\Actions\Content\ExtractContent;

class ExerciseContent{
    
    public function build($model,$enseignement=null){

        $canReadContnent = (new UserChecker)->canReadContent(
            auth()->userOrFail(),
            $enseignement ? $enseignement : $model
        );
        
        return [
            'enonce' => [
                'data' => $canReadContnent ?  $model->enonce : (new ExtractContent)->execute($model->enonce),
                'active' => (string) $model->active_enonce,
            ],
            'correction' => [
                'data' => $canReadContnent ?  $model->correction : (new ExtractContent)->execute($model->correction),
                'active' => (string) $model->active_correction,
            ]
        ];
    }


}