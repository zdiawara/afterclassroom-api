<?php

namespace App\Http\Controllers\Api;

use App\Matiere;

use App\Http\Controllers\Controller;
use App\Http\Resources\MatiereResource;

class MatiereController extends Controller
{

    public function index()
    {
        return MatiereResource::collection(Matiere::with("specialites")->get());
    }

    public function show(Matiere $matiere)
    {
        // dd($matiere);
    }
}
