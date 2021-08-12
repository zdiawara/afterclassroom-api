<?php

namespace App\Http\Requests;

use App\Http\Requests\CustumRequest;
use App\Http\Requests\EnseignementRules;
use App\Rules\CheckCollegeYear;
use App\Rules\CheckTrimestre;
use App\Rules\CheckTypeControle;

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
            'year' => 'required|digits:4',
            'type' => ['required', new CheckTypeControle],
            'trimestre' => ['required_if:type,devoir,composition', new CheckTrimestre],
            'enonce.active' => 'boolean',
            'correction.active' => 'boolean',
        ];
        return $this->makeRules(array_merge(EnseignementRules::getRules(), $rules));
    }
}
