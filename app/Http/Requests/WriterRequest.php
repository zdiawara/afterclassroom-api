<?php

namespace App\Http\Requests;

use App\Http\Requests\UserRequest;

class WriterRequest extends UserRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->teacher) {
            //$this->userId = $this->teacher->id;
        }
        return $this->makeRules(parent::rules());
    }
}
