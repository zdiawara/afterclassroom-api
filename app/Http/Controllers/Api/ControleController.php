<?php

namespace App\Http\Controllers\Api;

use App\Matiere;
use App\Controle;
use App\Http\Actions\Queries;
use App\Http\Controllers\Controller;
use App\Http\Requests\ControleRequest;
use App\Http\Resources\ControleResource;
use App\Http\Actions\Checker\UserChecker;
use App\Http\Resources\ControleCollection;
use Symfony\Component\HttpFoundation\Request;
use App\Http\Actions\Checker\EnseignementChecker;
use App\Http\Actions\Checker\TeacherMatiereChecker;


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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Queries $queries, Request $request)
    {
        //
        $teacher = $request->get('teacher');
        $type = $request->get('type');
        $trimestre = $request->get('trimestre');
        $subject = $request->get('subject');

        $query = Controle::whereHas('teacher.user', function ($q) use ($teacher) {
            $q->where('username', $teacher);
        });

        if ($type) {
            $query = $query->whereHas('type', function ($q) use ($type) {
                $q->where('code', $type);
            });
        }


        if ($trimestre) {
            $query = $query->whereHas('trimestre', function ($q) use ($trimestre) {
                $q->where('code', $trimestre);
            });
        } else if ($subject) {
            $query = $query->whereHas('subject', function ($q) use ($subject) {
                $q->where('code', $subject);
            });
        }

        $canReadInactive = $this->userChecker->canReadInactive($teacher);
        if (!$canReadInactive) {
            $query = $query->where('active_enonce', 1);
        }

        $result = []; //$queries->buildQuery($query, $request);

        //return new ControleCollection($result['query']->with(['type','teacher','subject'])->paginate(9,['*'], 'page', $result['page']));
        return new ControleCollection($result['query']->with(['type', 'trimestre', 'subject'])->get());
    }

    /**
     * Crée un controle
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
