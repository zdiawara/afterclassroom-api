<?php

namespace App\Http\Actions\Exercise;

use App\Exercise;
use App\Http\Actions\Checker\EnseignementChecker;
use App\Http\Actions\Checker\TeacherMatiereChecker;


class ManageExercise
{

    private EnseignementChecker $enseignementChecker;

    private TeacherMatiereChecker $teacherMatiereChecker;

    public function __construct(EnseignementChecker $enseignementChecker, TeacherMatiereChecker $teacherMatiereChecker)
    {
        $this->enseignementChecker = $enseignementChecker;
        $this->teacherMatiereChecker = $teacherMatiereChecker;
    }


    public function create(array $fields)
    {
        $exercise = new Exercise($fields);

        // Verifie que l'ut peut crée l'exercise
        $this->enseignementChecker->canCreate($exercise);

        $chapter = $exercise->chapter;

        // verifie que le teacher peut enseigner la matiere
        $this->teacherMatiereChecker->canTeach($chapter->teacher, $chapter->matiere, true);

        $lastExercise = Exercise::where("chapter_id", $exercise->chapter_id)
            ->orderBy("position", "desc")
            ->get()
            ->first();
        $exercise->position = isset($lastExercise) ? $lastExercise->position + 1 : 1;
        $exercise->save();
        return Exercise::find($exercise->id);
    }

    public function update(Exercise $exercise, array $fields)
    {
        // Verifie que l'ut connecté peut modifier le chapitre
        $this->enseignementChecker->canUpdate($exercise);

        $chapter = $exercise->chapter;

        // verifie que le teacher peut enseigner la matiere
        $this->teacherMatiereChecker->canTeach($chapter->teacher, $chapter->matiere, !(isset($fields['active_correction']) || isset($fields['active_enonce'])));

        $exercise->update($fields);

        $exercise->load('type');

        return $exercise;
    }
}
