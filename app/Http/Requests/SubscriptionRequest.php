<?php

namespace App\Http\Requests;

use App\Rules\CheckClasse;
use App\Rules\CheckMatiere;
use App\Rules\CheckStudent;
use App\Rules\CheckTeacher;
use App\Rules\CheckEnseignement;
use App\Http\Requests\CustumRequest;

class SubscriptionRequest extends CustumRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'classe' => ['required', new CheckClasse],
            'student' => ['required', new CheckStudent],
            'enseignement' => ['required', new CheckEnseignement],
            'matiere' => ['required_if:enseignement,basic', new CheckMatiere],
            'teacher' => ['required_if:enseignement,basic', new CheckTeacher],
        ];
        return $this->makeRules($rules);
    }
}
