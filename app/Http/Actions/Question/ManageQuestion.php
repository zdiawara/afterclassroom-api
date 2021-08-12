<?php

namespace App\Http\Actions\Question;

use App\Http\Actions\Checker\EnseignementChecker;
use App\Question;

class ManageQuestion
{

    private EnseignementChecker $enseignementChecker;

    public function __construct(EnseignementChecker $enseignementChecker)
    {
        $this->enseignementChecker = $enseignementChecker;
    }


    public function create(Question $question)
    {
        // Verifie que l'ut peut crée la question
        $this->enseignementChecker->canCreate($question);

        $question->save();

        return Question::find($question->id);
    }

    public function update(Question $question, array $fields)
    {
        // Verifie que l'ut connecté peut modifier la question
        $this->enseignementChecker->canUpdate($question);

        $question->update($fields);

        return $question;
    }
}
