<?php

namespace App\Http\Requests;

use App\Rules\CheckMatieres;
use App\Http\Requests\UserRequest;
use App\Http\Requests\CustumRequest;
use Illuminate\Foundation\Http\FormRequest;

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
            'matieres.*.code' => 'required',
            'matieres.*.level' => 'required'
        ];
        if ($this->teacher) {
            $this->userId = $this->teacher->user->userable_id;
        }
        return $this->makeRules(array_merge(parent::rules(), $rules));
    }
}
