<?php

namespace App\Http\Actions\Exercise;

use App\Exercise;
use App\Http\Actions\Checker\EnseignementChecker;
use App\Http\Actions\Checker\UserChecker;
use App\Http\Actions\Content\ReadContent;

class ShowExercise
{

    private EnseignementChecker $enseignementChecker;
    private UserChecker $userChecker;
    private ReadContent $readContent;

    public function __construct(ReadContent $readContent, EnseignementChecker $enseignementChecker, UserChecker $userChecker)
    {
        $this->readContent = $readContent;
        $this->enseignementChecker = $enseignementChecker;
        $this->userChecker = $userChecker;
    }


    public function execute(Exercise $exercise)
    {
        $chapter = $exercise->chapter;
        $teacher = $chapter->teacher->user->username;
        $this->enseignementChecker->checkCanReadInactive($exercise->is_enonce_active, $chapter->teacher->user->username);
        $params = [
            "matiere" => $chapter->matiere->code,
            "classe" => $chapter->classe->code,
        ];
        $canReadContent = $this->readContent->canReadByCode($teacher, $params);
        $_exercise = $this->readContent->byExercise($exercise, $canReadContent);
        if (!$exercise->is_correction_active && !$this->userChecker->canReadInactive($teacher)) {
            $_exercise->correction = "Contenu desactivé pour le moment";
        }
        return $_exercise;
    }
}
