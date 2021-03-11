<?php

namespace App\Http\Requests;

use App\Http\Requests\CustumRequest;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends CustumRequest {
    
    protected $userId;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'firstname' => 'required|max:50|min:2',
            'lastname' => 'required|max:50|min:2',
            //'username' => "required|max:50|min:3|unique:users,username,".$this->userId,
            'email' => 'required|email|max:50|unique:users,email,'.$this->userId,
            'password' => 'required|min:6',
            //'gender' => 'required',
            'avatar' => 'mimes:png,jpeg,jpg',
        ];
    }
}
