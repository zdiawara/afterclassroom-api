<?php

namespace App\Http\Controllers\Api;

use Request;
use App\Matiere;
use App\Specialite;
use App\Http\Controllers\Controller;

use App\Http\Resources\MatiereResource;
use App\Http\Resources\SpecialiteResource;

class MatiereController extends Controller
{

    public function index()
    {
        return MatiereResource::collection(Matiere::with("specialites")->get());
    }

    public function getSpecialites($id)
    {
        return SpecialiteResource::collection(Specialite::where('matiere_id', intval($id))->get());
    }

    public function specialites()
    {
        return $this->hasMany(Specialite::class);
    }
}
