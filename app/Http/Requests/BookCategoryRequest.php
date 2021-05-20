<?php

namespace App\Http\Requests;

use App\Http\Requests\CustumRequest;
use Illuminate\Foundation\Http\FormRequest;

class BookCategoryRequest extends CustumRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->makeRules([
            'categories.*.id' => 'required'
        ]);
    }
}
