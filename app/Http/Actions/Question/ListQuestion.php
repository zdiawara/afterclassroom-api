<?php

namespace App\Http\Actions\Question;

use App\Chapter;
use App\Http\Actions\Queries;
use App\Http\Actions\Checker\UserChecker;
use App\Http\Actions\Content\DataAccess;
use App\Http\Actions\Content\ReadContent;
use App\Http\Actions\MatiereTeacher\FindTeacherPrincipal;
use App\Notion;

class ListQuestion
{

    private UserChecker $userChecker;
    private ReadContent $readContent;
    private DataAccess $dataAccess;
    private FindTeacherPrincipal $findTeacherPrincipal;

    public function __construct(
        UserChecker $userChecker,
        ReadContent $readContent,
        DataAccess $dataAccess,
        FindTeacherPrincipal $findTeacherPrincipal
    ) {
        $this->userChecker = $userChecker;
        $this->readContent = $readContent;
        $this->dataAccess = $dataAccess;
        $this->findTeacherPrincipal = $findTeacherPrincipal;
    }

    public function execute(Notion $notion)
    {

        $query = $notion->questions();

        $teacher = $this->findTeacherPrincipal->execute($notion->matiere_id, $notion->classe_id);

        if (!isset($teacher) || !$this->userChecker->canReadInactive($teacher->id)) {
            $query = $query->where('is_active', 1);
        }

        $canAccessQuestion = $this->dataAccess->canAccessQuestion($teacher, [
            "matiere" => $notion->matiere_id,
            "classe" => $notion->classe_id
        ]);

        $questions = Queries::of($query)
            ->orderByPosition()
            ->get();

        return $questions->map(fn ($question) => $this->readContent->byQuestion($question, $canAccessQuestion));
    }
}
