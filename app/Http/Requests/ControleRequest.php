<?php

namespace App\Http\Requests;

use App\Http\Requests\CustumRequest;
use App\Http\Requests\EnseignementRules;

class ControleRequest extends CustumRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'year' => 'required_if:subject,final_examen',
            'type' => 'required',
            //'trimestre' => 'required_if:type,devoir',
            'enonce.active' => 'boolean',
            'correction.active' => 'boolean',
        ];
        return $this->makeRules(array_merge(EnseignementRules::getRules(), $rules));
    }
}
