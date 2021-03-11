<?php

namespace App\Http\Controllers\Api;

use App\Chapter;
use App\Exercise;
use App\Http\Actions\Queries;
use App\Http\Controllers\Controller;
use App\Http\Requests\ExerciseRequest;
use App\Http\Resources\ChapterResource;
use App\Http\Resources\ContentResource;
use App\Http\Resources\ExerciseResource;
use App\Http\Resources\ChapterCollection;
use App\Http\Actions\Exercise\CreateExercise;
use Symfony\Component\HttpFoundation\Request;
use App\Http\Actions\Checker\EnseignementChecker;
use App\Http\Actions\Checker\TeacherMatiereChecker;

class ExerciseController extends Controller
{
    private $enseignementChecker;

    private $teacherMatiereChecker;

    public function __construct(EnseignementChecker $enseignementChecker, TeacherMatiereChecker $teacherMatiereChecker)
    {
        $this->middleware(['auth:api']);
        $this->enseignementChecker = $enseignementChecker;
        $this->teacherMatiereChecker = $teacherMatiereChecker;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Queries $queries)
    {
        //
        $teacher = $request->get('teacher');

        $query = Chapter::whereHas('teacher.user', function ($q) use ($teacher) {
            $q->where('username', $teacher);
        });

        $result = $queries->buildQuery($query, $request);

        //return new ChapterCollection($result['query']->withCount('exercises')->get());

    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Exercise  $exercise
     * @return \Illuminate\Http\Response
     */
    public function show(Exercise $exercise)
    {
        $this->enseignementChecker->checkReadInactive($exercise, $exercise->exercisable->teacher);
        //
        $exercise->load(['type', 'content', 'solution.content', 'notions']);

        return new ExerciseResource($exercise);
    }

    /**
     * Création d'un exercice
     */
    public function store(ExerciseRequest $request)
    {
        $fields = $this->extractExerciseFields($request);

        $exercise = new Exercise($fields);

        // Verifie que l'ut peut crée l'exercise
        $this->enseignementChecker->canCreate($exercise);

        $chapter = $exercise->chapter;

        // verifie que le teacher peut enseigner la matiere
        $this->teacherMatiereChecker->canTeach($chapter->teacher, $chapter->matiere, true);

        $lastExercise = Exercise::where("chapter_id", $exercise->chapter_id)
            ->orderBy("position", "desc")
            ->get()
            ->first();

        if ($request->has('position')) {
            $exercise->position = $request->get('position');
        } else {
            $exercise->position = isset($lastExercise) ? $lastExercise->position + 1 : 1;
        }

        $exercise->save();

        $exercise->load('type');

        return new ExerciseResource($exercise);
    }

    /**
     * 
     */
    public function update(ExerciseRequest $request, Exercise $exercise)
    {

        // Verifie que l'ut connecté peut modifier le chapitre
        $this->enseignementChecker->canUpdate($exercise);

        $fields = $this->extractExerciseFields($request);

        $chapter = $exercise->chapter;

        // verifie que le teacher peut enseigner la matiere
        $this->teacherMatiereChecker->canTeach($chapter->teacher, $chapter->matiere, !(isset($fields['active_correction']) || isset($fields['active_enonce'])));

        $exercise->update($fields);

        $exercise->load('type', 'chapter');

        return $this->createdResponse(new ExerciseResource($exercise));
    }


    public function destroy(Exercise $exercise)
    {
        $exercise->delete();
        return $this->deletedResponse();
    }
}
