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

    public function passes($attribute, $id)
    {
        return Teacher::find($id) != null;
    }

    public function message()
    {
        return "Le professeur n'existe pas";
    }
}
