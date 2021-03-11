<?php

namespace App\Http\Requests;

use App\Http\Requests\CustumRequest;
use Illuminate\Foundation\Http\FormRequest;

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
            'teacher' => 'required',
            'classe' => 'required'
        ];
        return $this->makeRules($rules);
    }
}
