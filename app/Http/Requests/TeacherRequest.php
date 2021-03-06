<?php

namespace App\Http\Requests;

use App\Http\Requests\UserRequest;
use App\Rules\CheckMatiere;

class TeacherRequest extends UserRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'matieres' => 'required',
            'matieres.*.code' => ['required', new CheckMatiere],
            'matieres.*.level' => 'required'
        ];
        if ($this->teacher) {
            $this->userId = $this->teacher->id;
        }
        return $this->makeRules(array_merge(parent::rules(), $rules));
    }
}
