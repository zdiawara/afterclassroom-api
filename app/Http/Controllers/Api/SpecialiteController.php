<?php

namespace App\Http\Controllers\Api;

use Request;
use App\Matiere;
use App\Specialite;
use App\Http\Controllers\Controller;
use App\Http\Resources\SpecialiteResource;

class SpecialiteController extends Controller{

    public function index(Matiere $matiere){
        return SpecialiteResource::collection($matiere->specialites(['matiere_id'=>1])->get());
    }

}
