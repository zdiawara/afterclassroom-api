<?php

namespace App\Http\Requests;

use App\Rules\CheckClasse;
use App\Rules\CheckMatiere;
use App\Rules\CheckSpecialite;
use App\Http\Requests\CustumRequest;

class NotionRequest extends CustumRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'title' => 'required|max:100',
            'resume' => 'max:255',
            'content.active' => 'boolean',
            'position' => 'integer',
            'classe' => ['required', new CheckClasse],
            'matiere' => ['required', new CheckMatiere],
            'specialite' => [new CheckSpecialite],

        ];
        return $this->makeRules($rules);
    }
}
