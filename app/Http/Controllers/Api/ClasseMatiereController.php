<?php

namespace App\Http\Controllers\Api;

use App\ClasseMatiere;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClasseMatiereResource;

class ClasseMatiereController extends Controller
{


    public function __construct()
    {
    }

    public function index(Request $request)
    {
        $classeMatieres = ClasseMatiere::where(function ($query) use ($request) {
            if ($request->has('matiere')) {
                $query->where('matiere_id', $request->get('matiere'));
            }
        })->where(function ($query) use ($request) {
            if ($request->has('classe')) {
                $query->where('classe_id', $request->get('classe'));
            }
        })->where(function ($query) use ($request) {
            if ($request->has('teacher')) {
                $query->where('teacher_id', $request->get('teacher'));
            }
        })
            ->with(['classe', 'teacher', 'matiere'])
            ->get();

        return ClasseMatiereResource::collection($classeMatieres);
    }
}
