<?php

namespace App\Http\Actions\Checker;

use App\StudentTeacher;
use App\Http\Actions\Checker\Checker;
use Illuminate\Database\Eloquent\Model;


class UserChecker extends Checker
{

    /**
     * Verifie si l'ut peut acceder à un contenu inactif
     */
    public function canReadInactive(string $username)
    {
        $user = auth()->userOrFail();
        if ($user->isTeacher()) {
            return strtolower($user->username) === strtolower($username);
        }
        return false;
    }

    /**
     * Verifie si l'utilisateur connecté peut modifier le profil
     */
    public function canUpdate(Model $model)
    {
        // User
        $user = auth()->userOrFail();

        if (($user->isTeacher() || $user->isStudent()) && $model->id == $user->userable->id) {
            return true;
        }

        $this->updatePrivilegeException();
    }

    /**
     * Verifie si l'ut peut lire le contenu
     */
    public function studentcanAccessContent($user, $enseignement)
    {
        if (!isset($enseignement)) {
            return false;
        }
        return StudentTeacher::where('student_id', $user->userable->id)
            ->where('teacher_id', $enseignement->teacher_id)
            ->where('matiere_id', $enseignement->matiere_id)
            ->where('classe_id', $enseignement->classe_id)
            ->first() != null;
    }

    /**
     * Verifie si l'ut peut lire le contenu
     */
    public function canAccessContent($user, $enseignement)
    {
        // User
        if (!isset($user)) {
            return false;
        }

        if ($user->isStudent()) {
            return $this->studentcanAccessContent($user, $enseignement);
        } else if ($user->isTeacher() && $enseignement->teacher->user->username == $user->username) {
            return true;
        }

        return false;
    }
}
