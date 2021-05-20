<?php

namespace App\Http\Requests;

use App\Http\Requests\CustumRequest;
use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends CustumRequest
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
            'price' => 'nullable|integer',
            'cover' => 'mimes:png,jpeg,jpg',
            'content.active' => 'boolean',
            'classes' => 'required',
        ];
        return $this->makeRules(array_merge(
            collect(EnseignementRules::getRules())->except([
                'classe', 'classe.code'
            ])->all(),
            $rules
        ));
    }
}
