<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListChapterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'teacher' => 'required|string',
            'matiere' => 'required|string',
            'classe' => 'required|string',
            'specialite' => 'string'
        ];
    }
}
