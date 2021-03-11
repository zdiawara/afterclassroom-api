<?php

namespace App\Http\Controllers\Api;

use App\Matiere;
use App\Controle;
use App\Exercise;
use App\Constants\Code;
use App\Http\Actions\Queries;
use App\Http\Controllers\Controller;
use App\Http\Requests\ControleRequest;
use App\Http\Requests\ExerciseRequest;
use App\Http\Actions\Option\SyncOption;
use App\Http\Resources\ControleResource;
use App\Http\Resources\ExerciseResource;
use App\Http\Resources\ControleCollection;
use App\Http\Actions\Content\ManageContent;
use App\Http\Actions\Exercise\CreateExercise;
use Symfony\Component\HttpFoundation\Request;
use App\Http\Actions\Exercise\ControleExercise;
use App\Http\Actions\Checker\EnseignementChecker;
use App\Http\Actions\Checker\TeacherMatiereChecker;


class ControleController extends Controller
{
    private $enseignementChecker;

    private $teacherMatiereChecker;

    public function __construct(EnseignementChecker $enseignementChecker,TeacherMatiereChecker $teacherMatiereChecker)
    {
        $this->middleware(['auth:api']);
        $this->middleware('role:teacher',['only' => ['store','storeExercise','update']]);
        $this->enseignementChecker = $enseignementChecker;
        $this->teacherMatiereChecker = $teacherMatiereChecker;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Queries $queries , Request $request)
    {
        //
        $teacher = $request->get('teacher');
        $type = $request->get('type');

        $query = Controle::whereHas('teacher.user',function($q) use ($teacher){
            $q->where('username',$teacher);
        })->whereHas('type',function($q) use ($type){
            $q->where('code',$type);
        })->withCount('exercises');

        $result = $queries->buildQuery($query,$request);

        return new ControleCollection($result['query']->with(['type','teacher'])->paginate(9,['*'], 'page', $result['page']));
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ControleRequest $request,SyncOption $syncOption,ManageContent $manageContent)
    {
        //
        //
        $fields = $this->extractEnseignementFields($request);
        
        $controle = new Controle(array_merge(
            $request->only(['year','active']),
            ['type_id' => $request->get('type')],
            collect($fields)->except('options')->all()
        ));
        
        $this->enseignementChecker->canCreate($controle);
        
        $this->teacherMatiereChecker->canEdit($controle->teacher,$controle->matiere);
        
        $controle->save();
        
        $syncOption->execute($controle,$fields);
        
        $this->loadDependences($controle);

        // Enonce du controle
        $this->manageContent->create($controle);

        // Solution du controle
        $controle->solution()->save($solution = new Solution());
        
        $this->manageContent->create($solution);

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

    /**
     * 
     */
    public function showExercises(Controle $controle, ControleExercise $controleExercise)
    {
        return ExerciseResource::collection($controleExercise->execute(
            $controle
        ));
    }

    /**
     * 
     */
    public function showEnonce(Controle $controle, ControleExercise $controleExercise)
    {
        return ExerciseResource::collection($controleExercise->execute(
            $controle, Code::ENONCE
        ));
    }

    /**
     * 
     */
    public function showSolution(Controle $controle, ControleExercise $controleExercise)
    {
        return ExerciseResource::collection($controleExercise->execute(
            $controle, Code::SOLUTION
        ));
    }

     /**
     * 
     */
    public function storeExercise(ExerciseRequest $request, Controle $controle,  CreateExercise $createExercise)
    {
                
        $exercise = $createExercise->execute(new Exercise($this->extractExerciseFields($request)), $controle);
        
        return $this->createdResponse(new ExerciseResource($exercise));
        
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ControleRequest $request, Controle $controle,SyncOption $syncOption)
    {
        // Verifie que l'ut connecté peut créer modifier le controle
        $this->enseignementChecker->canUpdate($controle);

        $fields = array_merge(
            $request->only(['year','active']),
            $this->extractEnseignementFields($request)
        );

        if($request->has('type')){
            $fields['type_id'] = $request->get('type');
        }

        // Recupère la matiere pour verifier que le prof peut l'enseigner
        $matiere = isset($fields['matiere_id']) ? Matiere::find($fields['matiere_id']) : $controle->matiere;

        // Verifier que le chapitre peut être créer pour l'enseignant
        
        if(isset($fields['active'])){
            $this->teacherMatiereChecker->canTeach($controle->teacher,$matiere);
        }else{
            $this->teacherMatiereChecker->canEdit($controle->teacher,$matiere);
        }
                
        $controle->update(collect($fields)->except('options')->all());
        
        // Active ou desactive le contenu
        if(isset($fields['active'])){
            // Active ou desactive tous les exercices
        }

        // Sync les options
        $syncOption->execute($controle,$fields);

        $this->loadDependences($controle);
        
        return $this->createdResponse(new ControleResource($controle));
    }

    private function loadDependences(Controle $controle){
        $controle->load(['matiere','specialite','classe','options','type']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
