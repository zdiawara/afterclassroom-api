<?php

namespace App\Rules;

use App\Writer;
use Illuminate\Contracts\Validation\Rule;

class CheckWriter implements Rule
{

    public function __construct()
    {
        //
    }

    public function passes($attribute, $id)
    {
        return isset($id) ? Writer::find($id) != null : true;
    }

    public function message()
    {
        return "Le rédacteur n'existe pas";
    }
}
