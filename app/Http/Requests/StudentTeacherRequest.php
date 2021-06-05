<?php

namespace App\Http\Requests;

use App\Http\Requests\CustumRequest;
use App\Rules\CheckClasse;
use App\Rules\CheckEnseignement;
use App\Rules\CheckMatiere;

class StudentTeacherRequest extends CustumRequest
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
            'teacher' => 'string',
            'classe' => ['required', new CheckClasse],
            'enseignement' => ['required', new CheckEnseignement],
        ];
        return $this->makeRules($rules);
    }
}
