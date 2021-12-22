<?php

namespace App\Http\Requests;

use App\Http\Requests\CustumRequest;

class OrderRequest extends CustumRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'positions.*.id' => ['required'],
            'positions.*.value' => ['integer']
        ];
    }
}
