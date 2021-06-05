<?php

namespace App\Http\Controllers\Api;

use App\Matiere;
use App\ClasseMatiere;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClasseMatiereResource;
use App\Http\Resources\MatiereResource;

class ClasseMatiereController extends Controller
{


    public function __construct()
    {
    }

    public function index(Matiere $matiere)
    {
        $matiere->load('specialites');

        $classeMatieres = ClasseMatiere::where('matiere_id', $matiere->id)
            ->with(['classe', 'teacher'])
            ->get();
        return ClasseMatiereResource::collection($classeMatieres)
            ->additional(['matiere' => new MatiereResource($matiere)]);
    }
}
