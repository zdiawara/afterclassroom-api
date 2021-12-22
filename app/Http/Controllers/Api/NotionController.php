<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ListNotionRequest;
use App\Http\Resources\QuestionCollection;
use App\Http\Actions\Question\ListQuestion;
use App\Http\Actions\MatiereTeacher\FindTeacherPrincipal;
use App\Http\Actions\Notion\ListNotion;
use App\Http\Actions\Notion\ManageNotion;
use App\Http\Requests\NotionRequest;
use App\Http\Resources\NotionCollection;
use App\Http\Resources\NotionResource;
use App\Notion;

class NotionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);
        $this->middleware('role:teacher', ['only' => ['store', 'update', 'delete']]);
    }

    public function index(ListNotionRequest $request, ListNotion $listNotion, FindTeacherPrincipal $findTeacherPrincipal)
    {
        $params = $request->only(['classe', 'specialite', 'matiere']);
        $teacher = $findTeacherPrincipal->execute($params['matiere'], $params['classe']);
        if (!isset($teacher)) {
            return $this->conflictResponse('Aucun enseignant disponible');
        }
        return new NotionCollection($listNotion->execute($teacher->id, $params));
    }

    public function store(NotionRequest $request, ManageNotion $manageNotion)
    {
        $fields = $this->extractFields($request);
        $notion = $manageNotion->create(new Notion($fields));
        $this->loadDependences($notion);
        return $this->createdResponse(new NotionResource($notion));
    }

    public function update(NotionRequest $request, Notion $notion,  ManageNotion $manageNotion)
    {
        $fields = $this->extractFields($request);
        $manageNotion->update($notion, $fields);
        $this->loadDependences($notion);
        return $this->createdResponse(new NotionResource($notion));
    }

    /**
     * Affiche toutes les questions d'un chapitre
     */
    public function showQuestions(Notion $notion, ListQuestion $listQuestion)
    {
        return new QuestionCollection($listQuestion->execute($notion));
    }

    private function loadDependences(Notion $notion)
    {
        $notion->load(['matiere', 'specialite', 'classe']);
    }

    private function extractFields($request)
    {
        $data = $request->only(['title']);

        if ($request->has('active')) {
            $data['is_active'] = $request->get('active');
        }

        return array_merge(
            $data,
            $this->extractMatiere($request),
            $this->extractClasse($request)
        );
    }

    public function destroy(Notion $notion)
    {
        // supprime le chapter
        $notion->delete();
        return $this->deletedResponse();
    }
}
