<?php

namespace App\Http\Controllers\Api;

use App\Matiere;
use App\Controle;
use App\Http\Controllers\Controller;
use App\Http\Requests\ControleRequest;
use App\Http\Resources\ControleResource;
use App\Http\Actions\Checker\UserChecker;
use App\Http\Resources\ControleCollection;
use Symfony\Component\HttpFoundation\Request;
use App\Http\Actions\Checker\EnseignementChecker;
use App\Http\Actions\Checker\TeacherMatiereChecker;
use App\Http\Actions\Controle\ListControle;
use App\Http\Requests\ListControleRequest;

class ControleController extends Controller
{
    private $enseignementChecker;

    private $teacherMatiereChecker;

    private $userChecker;

    public function __construct(UserChecker $userChecker, EnseignementChecker $enseignementChecker, TeacherMatiereChecker $teacherMatiereChecker)
    {
        $this->middleware(['auth:api']);
        $this->middleware('role:teacher', ['only' => ['store', 'update']]);
        $this->enseignementChecker = $enseignementChecker;
        $this->teacherMatiereChecker = $teacherMatiereChecker;
        $this->userChecker = $userChecker;
    }

    public function index(ListControleRequest $request, ListControle $listControle)
    {
        $type = $request->get('type');
        $params = $request->only(['classe', 'trimestre', 'matiere', 'teacher']);
        return new ControleCollection($listControle->execute($type, $params));
    }


    public function store(ControleRequest $request)
    {
        //
        $fields = $this->extractEnseignementFields($request);

        $controle = new Controle(array_merge($this->extractControleFields($request), $fields));

        // Verifie que l'ut peut crée le controle
        $this->enseignementChecker->canCreate($controle);

        // verifie que le teacher peut enseigner la matiere
        $this->teacherMatiereChecker->canTeach($controle->teacher, $controle->matiere, true);

        $controle->save();

        $controle = Controle::find($controle->id);

        $this->loadDependences($controle);

        return $this->createdResponse(new ControleResource($controle));
    }

    public function update(ControleRequest $request, Controle $controle)
    {
        // Verifie que l'ut connecté peut créer modifier le controle
        $this->enseignementChecker->canUpdate($controle);

        $fields = array_merge(
            $this->extractControleFields($request),
            $this->extractEnseignementFields($request)
        );

        // Recupère la matiere pour verifier que le prof peut l'enseigner
        $matiere = isset($fields['matiere_id']) ? Matiere::find($fields['matiere_id']) : $controle->matiere;

        // verifie que le teacher peut enseigner la matiere
        $this->teacherMatiereChecker->canTeach($controle->teacher, $controle->matiere, !(isset($fields['active_correction']) || isset($fields['active_enonce'])));

        $controle->update($fields);

        $this->loadDependences($controle);

        return $this->createdResponse(new ControleResource($controle));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Controle $controle)
    {
        //
        $this->loadDependences($controle);

        return new ControleResource($controle);
    }


    private function loadDependences(Controle $controle)
    {
        $controle->load(['matiere', 'teacher', 'subject', 'classe', 'type', 'trimestre']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Controle $controle)
    {
        // supprime le controle
        $controle->delete();
        return $this->deletedResponse();
    }
}
