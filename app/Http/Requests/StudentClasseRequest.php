<?php

namespace App\Http\Requests;

use App\Rules\CheckClasse;
use App\Http\Requests\CustumRequest;

class StudentClasseRequest extends CustumRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            "classe" => ['required', new CheckClasse]
        ];
        return $this->makeRules($rules);
    }
}
