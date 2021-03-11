<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustumRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function makeRules($rules)
    {
        if(!is_array($rules)){
            return [];
        }
        if($this->isMethod('put') || $this->isMethod('patch')){
            return collect($rules)->filter(function($rule,$key){
                return $this->has($key);
            })->all();
        }
        return $rules;
    }
}
