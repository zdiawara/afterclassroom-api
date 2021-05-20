<?php

namespace App\Http\Actions\Question;

use App\Chapter;
use App\Question;
use Illuminate\Support\Facades\DB;
use App\Http\Actions\Checker\UserChecker;

class SearchQuestion
{

    private UserChecker $userChecker;

    public function __construct(UserChecker $userChecker)
    {
        $this->userChecker = $userChecker;
    }

    public function byTeacher(string $teacher, array $params = [])
    {

        $query = Question::whereHas('chapter', function ($q) use ($teacher, $params) {

            $q->whereHas('matiere', function ($queryChapter)  use ($params) {
                $queryChapter->where('code', $params['matiere']);
            })->whereHas('classe', function ($queryChapter)  use ($params) {
                $queryChapter->where('code', $params['classe']);
            })->whereHas('teacher.user', function ($queryChapter) use ($teacher) {
                $queryChapter->where(
                    DB::raw('lower(users.username)'),
                    strtolower($teacher)
                );
            });
        });

        if (isset($params['search'])) {
            $query = $query->where(DB::raw('lower(title)'), 'like', '%' . $params['search'] . '%');
        }

        $questions = $query->with('chapter')
            ->orderBy('title', 'asc')
            ->simplePaginate(10, ['*'], 'page', isset($params['page']) ?  $params['page'] : 0);

        return $questions;
    }

    public function byChapters(string $teacher, array $params = [])
    {

        $canReadInactive = $this->userChecker->canReadInactive($teacher);

        $query = Chapter::whereHas('matiere', function ($queryChapter)  use ($params) {
            $queryChapter->where('code', $params['matiere']);
        })->whereHas('classe', function ($queryChapter)  use ($params) {
            $queryChapter->where('code', $params['classe']);
        })->whereHas('teacher.user', function ($queryChapter) use ($teacher) {
            $queryChapter->where(DB::raw('lower(users.username)'), strtolower($teacher));
        })->withCount(['questions' => function ($query) use ($canReadInactive) {
            if (!$canReadInactive) {
                $query->where('is_active', 1);
            }
        }])
            ->with(['classe', 'matiere', 'specialite']);

        if (!$canReadInactive) {
            $query = $query->where('is_active', 1);
        }

        return $query->get();
    }
}
