<?php

namespace App\Http\Actions\MatiereTeacher;

use App\Classe;
use App\MatiereTeacher;

class FindMatiereTeacher
{

    public function __construct()
    {
    }


    /**
     * 
     */
    public function findPrincipalTeacher(string $matiere, string $classe)
    {
        $levelId = Classe::where('code', $classe)->firstOrFail()->level->id;
        $matiereTeacher = MatiereTeacher::whereHas('matiere', function ($q) use ($matiere) {
            $q->where('code', $matiere);
        })
            ->where('level_id', $levelId)
            ->where('is_principal', '1')
            ->first();
        if (isset($matiereTeacher)) {
            return $matiereTeacher->teacher;
        }
        return null;
    }
}
