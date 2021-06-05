<?php

namespace App\Rules;

use App\Teacher;
use Illuminate\Contracts\Validation\Rule;

class CheckTeacher implements Rule
{

    public function __construct()
    {
        //
    }

    public function passes($attribute, $username)
    {
        return isset($username) ? Teacher::find($username) != null : true;
    }

    public function message()
    {
        return "Le professeur n'existe pas";
    }
}
