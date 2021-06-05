<?php

namespace App\Http\Actions\Chapter;

use App\Chapter;
use App\Matiere;
use App\Http\Actions\Checker\EnseignementChecker;
use App\Http\Actions\Checker\TeacherMatiereChecker;


class ManageChapter
{

    private EnseignementChecker $enseignementChecker;

    private TeacherMatiereChecker $teacherMatiereChecker;

    public function __construct(EnseignementChecker $enseignementChecker, TeacherMatiereChecker $teacherMatiereChecker)
    {
        $this->enseignementChecker = $enseignementChecker;
        $this->teacherMatiereChecker = $teacherMatiereChecker;
    }


    public function create(Chapter $chapter)
    {
        // Verifie que l'ut peut crée le chapitre
        $this->enseignementChecker->canCreate($chapter);

        // verifie que le teacher peut enseigner la matiere
        $this->teacherMatiereChecker->canTeach($chapter->teacher, $chapter->matiere, true);

        $lastChapter = Chapter::where('teacher_id', $chapter->teacher_id)
            ->where('matiere_id', $chapter->matiere_id)
            ->where('classe_id', $chapter->classe_id)
            ->orderBy('position', 'desc')
            ->get()
            ->first();

        $chapter->position = isset($lastChapter) ? $lastChapter->position + 1 : 1;
        $chapter->save();

        return Chapter::find($chapter->id);
    }

    public function update(Chapter $chapter, array $fields)
    {
        // Verifie que l'ut connecté peut modifier le chapitre
        $this->enseignementChecker->canUpdate($chapter);

        // Recupère la matiere pour verifier que le prof peut l'enseigner
        $matiere = isset($fields['matiere_id']) ? $fields['matiere_id'] : $chapter->matiere_id;

        // verifie que le teacher peut enseigner la matiere
        $this->teacherMatiereChecker->canTeach($chapter->teacher, $matiere, !isset($fields['is_active']));

        $chapter->update($fields);

        return $chapter;
    }
}
