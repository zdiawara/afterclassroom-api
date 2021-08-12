<?php

namespace App\Http\Actions\Checker;

use App\Exercise;
use App\Http\Actions\Checker\Checker;
use Illuminate\Database\Eloquent\Model;
use App\Http\Actions\Checker\UserChecker;
use App\Http\Actions\MatiereTeacher\FindTeacherPrincipal;
use App\Notion;
use App\Question;

class EnseignementChecker extends Checker
{


    private UserChecker $userChecker;
    private FindTeacherPrincipal $findTeacherPrincipal;

    public function __construct(UserChecker $userChecker, FindTeacherPrincipal $findTeacherPrincipal)
    {
        $this->userChecker = $userChecker;
        $this->findTeacherPrincipal = $findTeacherPrincipal;
    }

    private function findNotionTeacher(Notion $notion)
    {
        $teacher = $this->findTeacherPrincipal->execute($notion->matiere_id, $notion->classe_id);
        if (isset($teacher)) {
            return $teacher->id;
        }
        return null;
    }

    private function createOrUpdate(Model $model)
    {

        $user = auth()->userOrFail();

        // Enseignant
        if ($user->isTeacher()) {
            $teacherId = null;
            if ($model instanceof Exercise) {
                $teacherId = $model->chapter->teacher_id;
            } else if ($model instanceof Question) {
                $teacherId = $this->findNotionTeacher($model->notion);
            } else if ($model instanceof Notion) {
                $teacherId = $this->findNotionTeacher($model);
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
