<?php

namespace App\Http\Actions;

class ExerciseContent
{

    public function build($model)
    {
        return [
            'enonce' => [
                'data' =>  $model->enonce,
                'active' => (string) $model->is_enonce_active,
            ],
            'correction' => [
                'data' => $model->correction,
                'active' => (string) $model->is_correction_active,
            ]
        ];
    }
}
