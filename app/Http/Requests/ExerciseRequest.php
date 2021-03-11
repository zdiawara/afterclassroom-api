<?php

namespace App\Http\Requests;

use App\Rules\CheckChapter;
use App\Rules\CheckTypeExercise;
use App\Http\Requests\CustumRequest;
use App\Http\Requests\EnseignementRules;
use Illuminate\Foundation\Http\FormRequest;

class ExerciseRequest extends CustumRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'type' => ['required', new CheckTypeExercise],
            'chapter' => ['required', new CheckChapter],
            'position' => 'integer',
            'enonce.active' => 'boolean',
            'correction.active' => 'boolean',
        ];
        return $this->makeRules(array_merge(
            $rules
        ));
    }
}
