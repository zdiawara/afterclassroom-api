<?php

namespace App\Http\Requests;

use App\Http\Requests\CustumRequest;
use App\Http\Requests\EnseignementRules;

class ChapterRequest extends CustumRequest
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
            'position' => 'integer'
        ];
        return $this->makeRules(array_merge(EnseignementRules::getRules(), $rules));
    }
}
