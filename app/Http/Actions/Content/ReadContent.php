<?php

namespace App\Http\Actions\Content;

use App\Chapter;
use App\Constants\CodeReferentiel;
use App\Controle;
use App\Question;
use Illuminate\Database\Eloquent\Model;


class ReadContent
{

    private string $defaultContent = "Abonnez-vous pour consulter ce contenu";

    public function byChapter(Chapter $chapter, bool $canReadContent)
    {
        if (!$canReadContent && !$chapter->is_public) {
            $chapter->content = $this->defaultContent;
        }
        return $chapter;
    }

    public function byExercise($exercise, bool $canReadContent)
    {
        if (!$canReadContent && !$exercise->is_public) {
            $exercise->correction = $this->defaultContent;
        }
        return $exercise;
    }

    public function byControle(Controle $controle, bool $canReadContent)
    {
        if ($controle->type->code === CodeReferentiel::EXAMEN) {
            if (!$canReadContent && !$controle->is_public) {
                $controle->correction = $this->defaultContent;
            }
            return $controle;
        }
        return $this->byExercise($controle, $canReadContent);
    }

    public function byQuestion(Question $question, bool $canReadQuestion)
    {
        if (!$canReadQuestion && !$question->is_public) {
            $question->content     = $this->defaultContent;
        }
        return $question;
    }
}
