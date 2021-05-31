<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuestionRequest;
use App\Http\Resources\QuestionResource;
use App\Http\Actions\Checker\EnseignementChecker;
use App\Http\Actions\Checker\TeacherMatiereChecker;
use App\Http\Actions\TeacherMatiere\FindTeacherMatiere;
use App\Http\Actions\Question\SearchQuestion;
use App\Http\Requests\ListQuestionRequest;
use App\Http\Resources\NotionResource;
use App\Question;

class QuestionController extends Controller
{
    private $enseignementChecker;

    private $teacherMatiereChecker;

    public function __construct(EnseignementChecker $enseignementChecker, TeacherMatiereChecker $teacherMatiereChecker)
    {
        $this->middleware(['auth:api']);
        $this->enseignementChecker = $enseignementChecker;
        $this->teacherMatiereChecker = $teacherMatiereChecker;
    }

    public function index(ListQuestionRequest $request, SearchQuestion $searchQuestion, FindTeacherMatiere $findTeacherMatiere)
    {
        $params = $request->only(['classe', 'matiere']);
        $teacher = $findTeacherMatiere->findPrincipalTeacher($params['matiere'], $params['classe']);
        return NotionResource::collection(isset($teacher) ? $searchQuestion->byChapters(
            $teacher->user->username,
            $params
        ) : []);
    }

    public function show(Question $question)
    {
        $question->load(['chapter']);
        return new QuestionResource($question);
    }

    /**
     * Création d'une question
     */
    public function store(QuestionRequest $request)
    {
        $fields = $this->extractQuestionFields($request);

        $question = new Question($fields);

        // Verifie que l'ut peut crée la question
        $this->enseignementChecker->canCreate($question);

        $chapter = $question->chapter;

        // verifie que le teacher peut enseigner la matiere
        $this->teacherMatiereChecker->canTeach($chapter->teacher, $chapter->matiere, true);


        $lastQuestion = Question::where("chapter_id", $question->chapter_id)
            ->orderBy("position", "desc")
            ->get()
            ->first();

        if ($request->has('position')) {
            $question->position = $request->get('position');
        } else {
            $question->position = isset($lastQuestion) ? $lastQuestion->position + 1 : 1;
        }

        $question->save();

        //$question->load('chapter');

        return new QuestionResource($question);
    }

    /**
     * 
     */
    public function update(QuestionRequest $request, Question $question)
    {

        // Verifie que l'ut connecté peut modifier la question
        $this->enseignementChecker->canUpdate($question);

        $fields = $this->extractQuestionFields($request);

        $chapter = $question->chapter;

        // verifie que le teacher peut enseigner la matiere
        $this->teacherMatiereChecker->canTeach($chapter->teacher, $chapter->matiere, !isset($fields['active']));

        $question->update($fields);

        return $this->createdResponse(new QuestionResource($question));
    }


    public function destroy(Question $question)
    {
        $question->delete();
        return $this->deletedResponse();
    }
}
