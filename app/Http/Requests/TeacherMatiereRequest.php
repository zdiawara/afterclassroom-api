<?php

namespace App\Http\Requests;

use App\Http\Requests\CustumRequest;
use App\Rules\CheckMatiere;

class TeacherMatiereRequest extends CustumRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'matiere' => ['required', new CheckMatiere],
            //'justificatif' => 'required|mimes:png,jpeg,pdf'
        ];
        return $this->makeRules($rules);
    }
}
