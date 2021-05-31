<?php

namespace App\Http\Actions\Exercise;

use App\Chapter;
use App\Http\Actions\Queries;
use App\Http\Actions\Content\DataAccess;
use App\Http\Actions\Checker\UserChecker;
use App\Http\Actions\Content\ReadContent;

class ListExercise
{

    private UserChecker $userChecker;
    private ReadContent $readContent;
    private DataAccess $dataAccess;

    public function __construct(UserChecker $userChecker, ReadContent $readContent, DataAccess $dataAccess)
    {
        $this->userChecker = $userChecker;
        $this->readContent = $readContent;
        $this->dataAccess = $dataAccess;
    }

    public function byChapter(Chapter $chapter)
    {

        $teacher = $chapter->teacher_id;

        $query = $chapter->exercises()->with(['type']);

        $canReadInactive = $this->userChecker->canReadInactive($teacher);

        $exercises = Queries::of($query)
            ->orderByPosition()
            ->addEnonceActive($canReadInactive)
            ->get();

        $canReadContent = $this->dataAccess->canReadContent($teacher, [
            "matiere" => $chapter->matiere_id,
            "classe" => $chapter->classe_id
        ]);

        // traiter les cas où la correction est inactifs
        if (!$canReadContent) {
            $exercises = $exercises
                ->map(function ($exercise) {
                    if (!$exercise->is_correction_active) {
                        $exercise->correction = "Ce contenu est inactif.";
                    }
                    return $exercise;
                });
        }

        $exercises = $exercises->map(fn ($exercise) => $this->readContent->byExercise($exercise, $canReadContent));

        return $exercises;
    }
}
