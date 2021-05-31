<?php

namespace App\Http\Requests;

use App\Http\Requests\UserRequest;
use App\Http\Requests\CustumRequest;
use App\Rules\CheckClasse;

class StudentRequest extends UserRequest
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
        if ($this->student) {
            $this->userId = $this->student->username;
        }
        return $this->makeRules(array_merge(parent::rules(), $rules));
    }
}
