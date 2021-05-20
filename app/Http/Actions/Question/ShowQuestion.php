<?php

namespace App\Http\Actions\Chapter;

use App\Chapter;
use App\Http\Actions\Checker\EnseignementChecker;
use App\Http\Actions\Checker\TeacherMatiereChecker;
use App\Http\Actions\Content\ReadContent;

class ShowChapter
{

    private EnseignementChecker $enseignementChecker;

    private ReadContent $readContent;

    public function __construct(ReadContent $readContent, EnseignementChecker $enseignementChecker, TeacherMatiereChecker $teacherMatiereChecker)
    {
        $this->readContent = $readContent;
        $this->enseignementChecker = $enseignementChecker;
        $this->teacherMatiereChecker = $teacherMatiereChecker;
    }


    public function execute(Chapter $chapter)
    {
        $teacher = $chapter->teacher->user->username;
        $this->enseignementChecker->checkCanReadInactive($chapter->is_active, $chapter->teacher->user->username);

        $params = [
            "matiere" => $chapter->matiere->code,
            "classe" => $chapter->classe->code,
        ];
        $canReadContent = false;
        return $this->readContent->byChapter($chapter, $canReadContent);
    }
}
