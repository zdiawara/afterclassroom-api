<?php

namespace App\Http\Actions\Content;

use App\Chapter;
use App\Http\Actions\Student\FindStudentTeacher;
use Illuminate\Database\Eloquent\Model;

class ReadContent
{

    private FindStudentTeacher $findStudentTeacher;

    public function __construct(FindStudentTeacher $findStudentTeacher)
    {
        $this->findStudentTeacher = $findStudentTeacher;
    }

    public function canReadByCode(string $teacher, array $params = [])
    {
        $user = auth()->userOrFail();
        if ($user->isTeacher() && $user->isOwner($teacher)) {
            return true;
        }
        if ($user->isStudent() && $this->findStudentTeacher->byCode($user, array_merge($params, ["teacher" => $teacher])) != null) {
            return true;
        }
        return false;
    }

    public function byChapter(Chapter $chapter, bool $canReadContent)
    {
        if (!$canReadContent && !$chapter->is_public) {
            $chapter->content = "Abonnez-vous pour consulter ce contenu";
        }
        return $chapter;
    }

    public function byExercise(Model $exercise, bool $canReadContent)
    {
        if (!$canReadContent && !$exercise->is_public) {
            $exercise->enonce     = "Abonnez-vous pour consulter ce contenu";
            $exercise->correction = "Abonnez-vous pour consulter ce contenu";
        }
        return $exercise;
    }
}
