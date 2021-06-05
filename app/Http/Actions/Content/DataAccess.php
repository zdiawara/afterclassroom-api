<?php

namespace App\Http\Actions\Content;

use App\Constants\CodeReferentiel;
use App\Http\Actions\Student\FindStudentTeacher;
use App\Http\Actions\Subscription\HasSubscription;

class DataAccess
{

    private HasSubscription $hasSubscription;

    public function __construct(HasSubscription $hasSubscription)
    {
        $this->hasSubscription = $hasSubscription;
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
            ["teacher" => $teacherId, 'enseignement' => $enseignement,]
        );
        if ($user->isStudent()) {
            return $this->hasSubscription->execute(
                array_merge($_params, ['student' => $user->username])
            );
        }
        return false;
    }
}
