<?php

namespace App\Http\Actions\Content;

use App\Constants\CodeReferentiel;
use App\Http\Actions\Student\FindStudentTeacher;

class DataAccess
{

    private FindStudentTeacher $findStudentTeacher;

    public function __construct(FindStudentTeacher $findStudentTeacher)
    {
        $this->findStudentTeacher = $findStudentTeacher;
    }

    public function canReadContent(string $teacher, array $params = [])
    {
        return $this->canRead(CodeReferentiel::BASIC, $teacher, $params);
    }

    public function canReadQuestion(string $teacher, array $params = [])
    {
        return $this->canRead(CodeReferentiel::FAQ, $teacher, $params);
    }

    public function canReadExamSubject(string $teacher, array $params = [])
    {
        return $this->canRead(CodeReferentiel::EXAM_SUBJECT, $teacher, $params);
    }

    private function canRead(string $enseignement, string $teacher, array $params = [])
    {
        $user = auth()->userOrFail();
        if ($user->isTeacher() && $user->isOwner($teacher)) {
            return true;
        }
        $_params = array_merge(
            $params,
            ["teacher" => $teacher, 'enseignement' => $enseignement]
        );
        if ($user->isStudent() && $this->findStudentTeacher->byCode($user, $_params) != null) {
            return true;
        }
        return false;
    }
}
