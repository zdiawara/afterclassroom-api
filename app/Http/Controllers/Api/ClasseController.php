<?php

namespace App\Http\Controllers\Api;

use App\Classe;
use App\ClasseMatiere;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClasseResource;
use App\Http\Resources\ClasseMatiereResource;

class ClasseController extends Controller
{

    /**
     * 
     */
    public function getOptions($classID)
    {
        return new ClasseResource(Classe::findOrFail($classID)->load(['options']));
    }

    public function index()
    {
        return ClasseResource::collection(Classe::with('level')->orderBy('position', 'asc')->get());
    }

    public function showMatieres(Classe $classe)
    {

        $matieres = ClasseMatiere::where('classe_id', $classe->id)
            ->with(['matiere', 'teacher'])
            ->get();

        return ClasseMatiereResource::collection($matieres)
            ->additional(['classe' => new ClasseResource($classe)]);
    }
}
