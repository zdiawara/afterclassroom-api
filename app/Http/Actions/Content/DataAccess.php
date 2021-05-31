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

    public function canReadContent(string $teacherId, array $paramsIds = [])
    {
        return $this->canRead(CodeReferentiel::BASIC, $teacherId, $paramsIds);
    }

    public function canReadQuestion(string $teacher, array $params = [])
    {
        return $this->canRead(CodeReferentiel::FAQ, $teacher, $params);
    }

    public function canReadExamSubject(string $teacher, array $params = [])
    {
        return $this->canRead(CodeReferentiel::EXAM_SUBJECT, $teacher, $params);
    }

    private function canRead(string $enseignement, string $teacherId, array $paramsIds = [])
    {
        $user = auth()->userOrFail();
        if ($user->isTeacher() && $user->isOwner($teacherId)) {
            return true;
        }
        $_params = array_merge(
            $paramsIds,
            ["teacher" => $teacherId, 'enseignement' => $enseignement]
        );
        if ($user->isStudent() && $this->findStudentTeacher->byId($user, $_params) != null) {
            return true;
        }
        return false;
    }
}
