<?php

namespace App\Http\Controllers\Api;

use App\Classe;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClasseResource;

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
        return ClasseResource::collection(Classe::with(['level', 'matieres.specialites'])->get());
    }
}
