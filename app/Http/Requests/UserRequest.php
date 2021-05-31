<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Http\Requests\CustumRequest;

class UserRequest extends CustumRequest
{

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
            'email' => ['required', 'max:50', 'email', Rule::unique('users')->where(function ($query) {
                return $query->where('username_', $this->userId);
            })],

            'password' => 'required|min:6',
        ];
    }
}
