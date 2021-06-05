<?php

namespace App\Rules;

use App\Student;
use Illuminate\Contracts\Validation\Rule;

class CheckStudent implements Rule
{

    public function __construct()
    {
        //
    }

    public function passes($attribute, $id)
    {
        return isset($id) ? Student::find($id) != null : true;
    }

    public function message()
    {
        return "L'étudiant n'existe pas";
    }
}
