<?php

namespace App\Rules;

use App\Chapter;
use Illuminate\Contracts\Validation\Rule;

class CheckChapter implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $id
     * @return bool
     */
    public function passes($attribute, $id)
    {
        return Chapter::find($id) != null;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Le chapitre n'existe pas";
    }
}
