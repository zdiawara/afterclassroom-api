<?php

namespace App\Http\Actions\Chapter;

use App\Chapter;
use App\Http\Actions\Content\DataAccess;
use App\Http\Actions\Content\ReadContent;
use App\Http\Actions\Checker\EnseignementChecker;
use App\Http\Actions\Checker\TeacherMatiereChecker;

class ShowChapter
{

    private EnseignementChecker $enseignementChecker;

    private ReadContent $readContent;
    private DataAccess $dataAccess;

    public function __construct(DataAccess $dataAccess, ReadContent $readContent, EnseignementChecker $enseignementChecker, TeacherMatiereChecker $teacherMatiereChecker)
    {
        $this->readContent = $readContent;
        $this->enseignementChecker = $enseignementChecker;
        $this->teacherMatiereChecker = $teacherMatiereChecker;
        $this->dataAccess = $dataAccess;
    }


    public function execute(Chapter $chapter)
    {
        $teacher = $chapter->teacher_id;
        $this->enseignementChecker->checkCanReadInactive($chapter->is_active, $chapter->teacher_id);

        $params = [
            "matiere" => $chapter->matiere_id,
            "classe" => $chapter->classe_id,
        ];
        $canReadContent = $this->dataAccess->canReadContent($teacher, $params);
        return $this->readContent->byChapter($chapter, $canReadContent);
    }
}
