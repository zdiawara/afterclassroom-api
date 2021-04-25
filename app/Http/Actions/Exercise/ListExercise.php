<?php

namespace App\Http\Actions\Exercise;

use App\Chapter;
use App\Http\Actions\Queries;
use App\Http\Actions\Checker\UserChecker;
use App\Http\Actions\Content\ReadContent;

class ListExercise
{

    private UserChecker $userChecker;
    private ReadContent $readContent;

    public function __construct(UserChecker $userChecker, ReadContent $readContent)
    {
        $this->userChecker = $userChecker;
        $this->readContent = $readContent;
    }

    public function byChapter(Chapter $chapter)
    {
        $teacher = $chapter->teacher->user->username;

        $query = $chapter->exercises()->with(['type']);

        if (!$this->userChecker->canReadInactive($teacher)) {
            $query = $query->where('is_enonce_active', 1);
        }

        $canReadContent = $this->readContent->canReadByCode($teacher, [
            "matiere" => $chapter->matiere->code,
            "classe" => $chapter->classe->code
        ]);

        $exercises = Queries::of($query)
            ->orderByPosition()
            ->get();

        $exercises = $exercises->map(fn ($exercise) => $this->readContent->byExercise($exercise, $canReadContent));

        if ($this->userChecker->canReadInactive($teacher)) {
            return $exercises;
        }
        // traiter les cas où la correction est inactifs
        return $exercises
            ->map(function ($exercise) {
                if (!$exercise->is_correction_active) {
                    $exercise->correction = null;
                }
                return $exercise;
            });
    }
}
