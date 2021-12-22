<?php

namespace App\Http\Actions\Content;

use App\Constants\CodeReferentiel;
use App\Http\Actions\Subscription\HasSubscription;

class DataAccess
{

    private HasSubscription $hasSubscription;

    public function __construct(HasSubscription $hasSubscription)
    {
        $this->hasSubscription = $hasSubscription;
    }

    public function canAccessContent(string $teacherId, array $paramsIds = [])
    {
        return $this->canAccess(CodeReferentiel::BASIC, $teacherId, $paramsIds);
    }

    public function canAccessQuestion(string $teacher, array $params = [])
    {
        return $this->canAccess(CodeReferentiel::FAQ, $teacher, $params);
    }

    public function canAccessExamSubject(string $teacher, array $params = [])
    {
        return $this->canAccess(CodeReferentiel::EXAM_SUBJECT, $teacher, $params);
    }

    private function canAccess(string $enseignement, string $teacherId, array $paramsIds = [])
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
