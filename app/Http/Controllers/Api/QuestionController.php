<?php

namespace App\Http\Controllers\Api;

use App\Question;
use Illuminate\Http\Response;
use App\Http\Requests\OrderRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\QuestionRequest;
use App\Http\Resources\NotionResource;
use App\Http\Resources\QuestionResource;
use App\Http\Requests\ListQuestionRequest;
use App\Http\Actions\Question\ManageQuestion;
use App\Http\Actions\Question\SearchQuestion;
use App\Http\Actions\Checker\EnseignementChecker;
use App\Http\Actions\MatiereTeacher\FindTeacherPrincipal;

class QuestionController extends Controller
{
    private $enseignementChecker;

    public function __construct(EnseignementChecker $enseignementChecker)
    {
        $this->middleware(['auth:api']);
        $this->enseignementChecker = $enseignementChecker;
    }

    public function index(
        ListQuestionRequest $request,
        SearchQuestion $searchQuestion,
        FindTeacherPrincipal $findTeacherPrincipal
    ) {
        $params = $request->only(['classe', 'matiere']);
        $teacher = $findTeacherPrincipal->execute($params['matiere'], $params['classe']);
        return NotionResource::collection(isset($teacher) ? $searchQuestion->byChapters(
            $teacher->user->username,
            $params
        ) : []);
    }

    public function show(Question $question)
    {
        $question->load(['notion']);
        return new QuestionResource($question);
    }

    /**
     * Création d'une question
     */
    public function store(QuestionRequest $request, ManageQuestion $manageQuestion)
    {
        $question = new Question($this->extractQuestionFields($request));
        return  $this->createdResponse(new QuestionResource(
            $manageQuestion->create($question)
        ));
    }

    /**
     * 
     */
    public function update(QuestionRequest $request, Question $question, ManageQuestion $manageQuestion)
    {

        // Verifie que l'ut connecté peut modifier la question
        $this->enseignementChecker->canUpdate($question);

        $fields = $this->extractQuestionFields($request);

        return $this->createdResponse(new QuestionResource(
            $manageQuestion->update($question, $fields)
        ));
    }

    public function updatePositions(OrderRequest $request, ManageQuestion $manageQuestion)
    {
        $positions = $request->get('positions');


        $manageQuestion->updatePositions($positions);

        return response()->json(['message' => 'Positions ont été modifiées'], Response::HTTP_OK);
    }


    public function destroy(Question $question)
    {
        $question->delete();
        return $this->deletedResponse();
    }
}
