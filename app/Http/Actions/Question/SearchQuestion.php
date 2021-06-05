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

    public function byTeacher(string $teacherId, array $params = [])
    {

        $query = Question::whereHas('chapter', function ($q) use ($teacherId, $params) {
            $q->where('matiere_id', $params['matiere'])
                ->where('classe_id', $params['classe'])
                ->where('teacher_id', $teacherId);
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

        $query = Chapter::where('matiere_id', $params['matiere'])
            ->where('classe_id', $params['classe'])
            ->where('teacher_id', $teacher)
            ->with(['classe', 'matiere', 'specialite']);

        if (!$canReadInactive) {
            $query = $query->where('is_active', 1);
        }

        return $query->get();
    }
}
