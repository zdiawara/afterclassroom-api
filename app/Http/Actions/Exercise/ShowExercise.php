<?php

namespace App\Http\Actions\Exercise;

use App\Exercise;
use App\Http\Actions\Content\DataAccess;
use App\Http\Actions\Checker\UserChecker;
use App\Http\Actions\Content\ReadContent;
use App\Http\Actions\Checker\EnseignementChecker;

class ShowExercise
{

    private EnseignementChecker $enseignementChecker;
    private UserChecker $userChecker;
    private ReadContent $readContent;
    private DataAccess $dataAccess;

    public function __construct(ReadContent $readContent, EnseignementChecker $enseignementChecker, UserChecker $userChecker, DataAccess $dataAccess)
    {
        $this->readContent = $readContent;
        $this->enseignementChecker = $enseignementChecker;
        $this->userChecker = $userChecker;
        $this->dataAccess = $dataAccess;
    }


    public function execute(Exercise $exercise)
    {
        $chapter = $exercise->chapter;
        $teacher = $chapter->teacher_id;

        $this->enseignementChecker->checkCanReadInactive($exercise->is_enonce_active, $teacher);

        $params = ["matiere" => $chapter->matiere_id, "classe" => $chapter->classe_id,];

        $canReadContent = $this->dataAccess->canReadContent($teacher, $params);

        $_exercise = $this->readContent->byExercise($exercise, $canReadContent);

        if (!$exercise->is_correction_active && !$this->userChecker->canReadInactive($teacher)) {
            $_exercise->correction = "Contenu desactivé pour le moment";
        }
        return $_exercise;
    }
}
