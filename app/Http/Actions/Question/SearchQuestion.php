<?php

namespace App\Http\Actions\Question;

use App\Chapter;
use App\Question;
use Illuminate\Support\Facades\DB;
use App\Http\Actions\MatiereTeacher\FindMatiereTeacher;

class SearchQuestion
{

    private FindMatiereTeacher $findMatiereTeacher;

    public function __construct(FindMatiereTeacher $findMatiereTeacher)
    {
        $this->findMatiereTeacher = $findMatiereTeacher;
    }

    public function byTeacher(array $params = [])
    {
        $teacher = $this->findMatiereTeacher->findPrincipalTeacher($params['matiere'], $params['classe']);

        if (!isset($teacher)) {
            return [];
        }

        $username = $teacher->user->username;

        $query = Question::whereHas('chapter', function ($q) use ($username, $params) {

            $q->whereHas('matiere', function ($queryChapter)  use ($params) {
                if (!isset($params['chapterId'])) {
                    $queryChapter->where('code', $params['matiere']);
                }
            })->whereHas('classe', function ($queryChapter)  use ($params) {
                if (!isset($params['chapterId'])) {
                    $queryChapter->where('code', $params['classe']);
                }
            })->whereHas('teacher.user', function ($queryChapter) use ($username) {
                $queryChapter->where(
                    DB::raw('lower(users.username)'),
                    strtolower($username)
                );
            });
        });

        if (isset($params['search'])) {
            $query = $query->where(DB::raw('lower(title)'), 'like', '%' . $params['search'] . '%');
        }

        if (isset($params['chapterId'])) {
            $query = $query->where('chapter_id', $params['chapterId']);
        }


        $questions = $query->with('chapter')
            ->orderBy('title', 'asc')
            ->simplePaginate(10, ['*'], 'page', isset($params['page']) ?  $params['page'] : 0);

        return $questions;
    }

    public function byChapters(array $params = [])
    {
        $teacher = $this->findMatiereTeacher->findPrincipalTeacher($params['matiere'], $params['classe']);
        if (!isset($teacher)) {
            return [];
        }
        $chapters = Chapter::whereHas('matiere', function ($queryChapter)  use ($params) {
            $queryChapter->where('code', $params['matiere']);
        })->whereHas('classe', function ($queryChapter)  use ($params) {
            $queryChapter->where('code', $params['classe']);
        })->whereHas('teacher.user', function ($queryChapter) use ($teacher) {
            $queryChapter->where(DB::raw('lower(users.username)'), strtolower($teacher->user->username));
        })->get();
        return $chapters;
    }
}
