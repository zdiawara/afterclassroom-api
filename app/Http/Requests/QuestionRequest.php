<?php

namespace App\Http\Requests;

use App\Http\Requests\CustumRequest;
use App\Rules\CheckNotion;

class QuestionRequest extends CustumRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'title' => 'required',
            'notion' => ['required', new CheckNotion],
            'content.active' => 'boolean',
        ];
        return $this->makeRules(array_merge(
            $rules
        ));
    }
}
