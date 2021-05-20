<?php

namespace App\Http\Requests;

use App\Http\Requests\UserRequest;
use App\Http\Requests\CustumRequest;

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
            "classe" => 'required'
        ];
        if($this->student){
            $this->userId = $this->student->user->userable->id;
        }
        return $this->makeRules(array_merge(parent::rules(), $rules));
    }
}
