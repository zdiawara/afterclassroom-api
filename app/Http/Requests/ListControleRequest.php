<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListControleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'required|string',
            'matiere' => 'required|string',
            'classe' => 'required|string',
            'teacher' => 'required_if:type,composition,devoir',
            'trimestre' => 'required_if:type,composition,devoir',
        ];
    }
}
