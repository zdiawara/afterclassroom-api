<?php

namespace App\Http\Actions\Chapter;

use App\Chapter;
use App\Http\Actions\Queries;
use Illuminate\Support\Facades\DB;
use App\Http\Actions\Checker\UserChecker;
use App\Http\Actions\Content\DataAccess;
use App\Http\Actions\Content\ReadContent;

class SearchChapter
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

    public function byTeacher(string $teacher, array $params = [])
    {
        $query = Chapter::whereHas('teacher.user', function ($q) use ($teacher) {
            $q->where(DB::raw('lower(users.username)'), strtolower($teacher));
        })->with(['specialite']);

        if (!$this->userChecker->canReadInactive($teacher)) {
            $query = $query->where('is_active', 1);
        }
        $chapters = Queries::of($query)
            ->addMatiere($params)
            ->addSpecialite($params)
            ->addClasse($params)
            ->orderByPosition()
            ->get();

        $canReadContent = $this->dataAccess->canReadContent($teacher, $params);
        return $chapters->map(fn ($chapter) => $this->readContent->byChapter($chapter, $canReadContent));
    }
}
