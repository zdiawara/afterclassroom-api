<?php

namespace App\Http\Controllers\Api;

use App\Chapter;
use App\Exercise;
use App\Http\Controllers\Controller;
use App\Http\Requests\ExerciseRequest;
use App\Http\Resources\ExerciseResource;
use App\Http\Actions\Exercise\ManageExercise;
use Symfony\Component\HttpFoundation\Request;
use App\Http\Actions\Checker\EnseignementChecker;
use App\Http\Actions\Exercise\ShowExercise;

class ExerciseController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);
    }

    public function show(Exercise $exercise, ShowExercise $showExercise)
    {
        $_exercise = $showExercise->execute($exercise);
        $_exercise->load(['type']);
        return new ExerciseResource($_exercise);
    }

    /**
     * Créer d'un exercice
     */
    public function store(ExerciseRequest $request, ManageExercise $manageExercise)
    {
        $exercise = $manageExercise->create($this->extractExerciseFields($request));
        $exercise->load('type');
        return $this->createdResponse(new ExerciseResource($exercise));
    }

    /**
     * Mettre à jour un exercice
     */
    public function update(ExerciseRequest $request, Exercise $exercise, ManageExercise $manageExercise)
    {
        $_exercise = $manageExercise->update($exercise, $this->extractExerciseFields($request));
        $_exercise->load('type');
        return $this->createdResponse(new ExerciseResource($_exercise));
    }


    public function destroy(Exercise $exercise)
    {
        $exercise->delete();
        return $this->deletedResponse();
    }
}
