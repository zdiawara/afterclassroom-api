<?php

namespace App\Http\Requests;

use App\Http\Requests\CustumRequest;
use App\Rules\CheckWriter;

class TeacherWriterRequest extends CustumRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'writer' => ['required', new CheckWriter],
        ];
        return $this->makeRules($rules);
    }
}
