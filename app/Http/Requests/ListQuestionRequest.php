<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListQuestionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'matiere' => 'required|string',
            'classe' => 'required|string',
            // 'teacher' => 'required|string',
        ];
    }
}
