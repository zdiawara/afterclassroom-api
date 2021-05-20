<?php

namespace App\Http\Requests;

use App\Rules\CheckChapter;
use App\Http\Requests\CustumRequest;

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
            'chapter' => ['required', new CheckChapter],
            'content.active' => 'boolean',
        ];
        return $this->makeRules(array_merge(
            $rules
        ));
    }
}
