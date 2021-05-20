<?php

namespace App\Http\Actions\Question;

use App\Chapter;
use App\Http\Actions\Queries;
use App\Http\Actions\Checker\UserChecker;
use App\Http\Actions\Content\DataAccess;
use App\Http\Actions\Content\ReadContent;

class ListQuestion
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
        $teacher = $chapter->teacher->user->username;

        $query = $chapter->questions();

        if (!$this->userChecker->canReadInactive($teacher)) {
            $query = $query->where('is_active', 1);
        }

        $canReadQuestion = $this->dataAccess->canReadQuestion($teacher, [
            "matiere" => $chapter->matiere->code,
            "classe" => $chapter->classe->code
        ]);

        $questions = Queries::of($query)
            //->orderByPosition()
            ->get();

        return $questions->map(fn ($question) => $this->readContent->byQuestion($question, $canReadQuestion));
    }
}
