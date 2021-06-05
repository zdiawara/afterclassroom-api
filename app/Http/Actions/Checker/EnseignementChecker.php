<?php

namespace App\Http\Actions\Checker;

use App\Teacher;
use App\Exercise;
use App\Http\Actions\Checker\Checker;
use Illuminate\Database\Eloquent\Model;
use App\Http\Actions\Checker\UserChecker;
use App\Question;

class EnseignementChecker extends Checker
{


    private $userChecker;

    public function __construct(UserChecker $userChecker)
    {
        $this->userChecker = $userChecker;
    }

    private function createOrUpdate(Model $model)
    {

        $user = auth()->userOrFail();

        // Enseignant
        if ($user->isTeacher()) {
            $teacherId = null;
            if ($model instanceof Exercise || $model instanceof Question) {
                $teacherId = $model->chapter->teacher_id;
            } else {
                $teacherId = $model->teacher_id;
            }
            if ($teacherId == $user->userable_id) {
                return true;
            }
        }

        $this->updatePrivilegeException();
    }

    public function canCreate(Model $model)
    {
        return $this->createOrUpdate($model);
    }

    /**
     * Verifie si l'utilisateur connecté peut modifier l'enseignement
     */
    public function canUpdate(Model $model)
    {
        return $this->createOrUpdate($model);
    }

    public function checkCanReadInactive(bool $is_active, string $teacher)
    {
        if (!$is_active && !$this->userChecker->canReadInactive($teacher)) {
            $this->badRequestException("Cette resource n'est pas accessible pour le moment !");
        }
    }
}
