<?php

namespace App\Http\Actions\Notion;

use App\Chapter;
use App\Http\Actions\Checker\EnseignementChecker;
use App\Http\Actions\MatiereTeacher\FindTeacherPrincipal;
use App\Notion;

class ManageNotion
{

    private EnseignementChecker $enseignementChecker;

    public function __construct(EnseignementChecker $enseignementChecker)
    {
        $this->enseignementChecker = $enseignementChecker;
    }


    public function create(Notion $notion)
    {
        // Verifie que l'ut peut crée la notion
        $this->enseignementChecker->canCreate($notion);

        $last = Notion::where('matiere_id', $notion->matiere_id)
            ->where('classe_id', $notion->classe_id)
            ->orderBy('position', 'desc')
            ->get()
            ->first();

        $notion->position = isset($last) ? $last->position + 1 : 1;
        $notion->save();

        return Notion::find($notion->id);
    }

    public function update(Notion $notion, array $fields)
    {
        // Verifie que l'ut connecté peut modifier la notion
        $this->enseignementChecker->canUpdate($notion);

        $notion->update($fields);

        return $notion;
    }
}
