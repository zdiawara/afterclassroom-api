<?php

namespace App\Http\Controllers\Api;

use App\Chapter;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChapterRequest;
use App\Http\Resources\ChapterResource;
use App\Http\Resources\TeacherResource;
use App\Http\Actions\Chapter\ShowChapter;
use App\Http\Actions\Checker\UserChecker;
use App\Http\Requests\ListChapterRequest;
use App\Http\Resources\ChapterCollection;
use App\Http\Resources\ExerciseCollection;
use App\Http\Resources\QuestionCollection;
use App\Http\Actions\Chapter\ManageChapter;
use App\Http\Actions\Chapter\ListChapter;
use App\Http\Actions\Exercise\ListExercise;
use App\Http\Actions\Question\ListQuestion;

class ChapterController extends Controller
{
    public function __construct(UserChecker $userChecker)
    {
        $this->middleware(['auth:api']);
        $this->middleware('role:teacher', ['only' => ['store', 'update', 'delete']]);
        $this->userChecker = $userChecker;
    }

    public function index(ListChapterRequest $request, ListChapter $listChapter)
    {
        $teacher = $request->get('teacher');
        $params = $request->only(['classe', 'specialite', 'matiere']);
        return new ChapterCollection($listChapter->execute($teacher, $params));
    }

    public function show(Chapter $chapter, ShowChapter $showChapter)
    {
        $_chapter = $showChapter->execute($chapter);
        $this->loadDependences($_chapter);
        return new ChapterResource($_chapter);
    }

    public function store(ChapterRequest $request, ManageChapter $manageChapter)
    {
        $fields = array_merge(
            $this->extractChapterFields($request),
            $this->extractEnseignementFields($request)
        );
        $chapter = $manageChapter->create(new Chapter($fields));
        $this->loadDependences($chapter);
        return $this->createdResponse(new ChapterResource($chapter));
    }

    public function update(ChapterRequest $request, Chapter $chapter, ManageChapter $manageChapter)
    {
        $fields = array_merge(
            $this->extractChapterFields($request),
            $this->extractEnseignementFields($request)
        );
        $manageChapter->update($chapter, $fields);
        $this->loadDependences($chapter);
        return $this->createdResponse(new ChapterResource($chapter));
    }

    /**
     * Affiche tous les exercices d'un chapitre
     */
    public function showExercises(Chapter $chapter, ListExercise $listExercise)
    {
        return new ExerciseCollection($listExercise->byChapter($chapter));
    }

    /**
     * Affiche toutes les questions d'un chapitre
     */
    public function showQuestions(Chapter $chapter, ListQuestion $listQuestion)
    {
        return new QuestionCollection($listQuestion->byChapter($chapter));
    }

    // charge les dependences liées au professeur
    private function loadDependences(Chapter $chapter)
    {
        $chapter->load(['matiere', 'specialite', 'classe', 'teacher']);
    }

    private function extractChapterFields($request)
    {
        $data = $request->only(['title', 'resume']);
        $content = $request->get("content");
        if ($content) {
            if (isset($content['data'])) {
                $data['content'] = $content['data'];
            }
            if (isset($content['active'])) {
                $data['is_active'] = $content['active'];
            }
        }
        if ($request->has('public')) {
            $data['is_public'] = $request->get('public');
        }
        return $data;
    }

    public function destroy(Chapter $chapter)
    {
        // supprime le chapter
        $chapter->delete();
        return $this->deletedResponse();
    }
}
