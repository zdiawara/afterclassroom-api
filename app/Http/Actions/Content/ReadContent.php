<?php

namespace App\Http\Actions\Content;

use App\Chapter;
use App\Constants\CodeReferentiel;
use App\Controle;
use App\Question;

class ReadContent
{

    private string $defaultContent = "Abonnez-vous pour consulter ce contenu";

    public function byChapter(Chapter $chapter, bool $canAccessContent)
    {
        if (!$canAccessContent && !$chapter->is_public) {
            $chapter->content = $this->defaultContent;
        }
        return $chapter;
    }

    public function byExercise($exercise, bool $canAccessContent)
    {
        if (!$canAccessContent && !$exercise->is_public) {
            $exercise->correction = $this->defaultContent;
        }
        return $exercise;
    }

    public function byControle(Controle $controle, bool $canAccessContent)
    {
        if ($controle->type_id === CodeReferentiel::EXAMEN) {
            if (!$canAccessContent && !$controle->is_public) {
                $controle->correction = $this->defaultContent;
            }
            return $controle;
        }
        return $this->byExercise($controle, $canAccessContent);
    }

    public function byQuestion(Question $question, bool $canAccessQuestion)
    {
        if (!$canAccessQuestion && !$question->is_public) {
            $question->content     = $this->defaultContent;
        }
        return $question;
    }
}
