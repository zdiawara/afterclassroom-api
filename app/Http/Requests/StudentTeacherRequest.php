<?php

namespace App\Http\Requests;

use App\Http\Requests\CustumRequest;

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
            'matiere' => 'required',
            'teacher' => 'string',
            'classe' => 'required',
            'enseignement' => 'required',
        ];
        return $this->makeRules($rules);
    }
}
