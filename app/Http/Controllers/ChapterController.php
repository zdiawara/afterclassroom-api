<?php

namespace App\Http\Controllers;

use App\Chapter;
use App\Matiere;
use App\Http\Actions\Queries;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChapterRequest;
use App\Http\Actions\Option\SyncOption;
use App\Http\Resources\ChapterResource;
use App\Http\Actions\Checker\UserChecker;
use App\Http\Resources\ChapterCollection;
use App\Http\Resources\ExerciseCollection;
use Symfony\Component\HttpFoundation\Request;
use App\Http\Actions\Checker\EnseignementChecker;
use App\Http\Actions\Checker\TeacherMatiereChecker;
use App\Http\Resources\QuestionCollection;

class ChapterController extends Controller
{
    private $enseignementChecker;

    private $teacherMatiereChecker;

    private $userChecker;

    public function __construct(UserChecker $userChecker, EnseignementChecker $enseignementChecker, TeacherMatiereChecker $teacherMatiereChecker)
    {
        $this->middleware(['auth:api']);
        $this->middleware('role:teacher', ['only' => ['store', 'update', 'storeExercise']]);
        $this->enseignementChecker = $enseignementChecker;
        $this->teacherMatiereChecker = $teacherMatiereChecker;
        $this->userChecker = $userChecker;
    }

    private function extractChapterFields($request)
    {
        $data = $request->only(['title', 'resume']);
        $content = $request->get("content");
        if ($content) {
            if (isset($content['data'])) {
                $data['content'] = $content['data'];
            }
            if (isset($content['active'])) {
                $data['active'] = $content['active'];
            }
        }
        return $data;
    }

    /**
     * Liste des chapitres en fonction du teacher
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Queries $queries)
    {
        // Teacher
        $teacher = $request->get('teacher');

        $query = Chapter::whereHas('teacher.user', function ($q) use ($teacher) {
            $q->where('username', $teacher);
        })->with(['specialite']);

        // Le teacher peut consulter les chapters inactifs
        $query = $queries->addActive($query, $this->userChecker->canReadInactive($teacher));

        // Ajoute filtres matiere / classe / page
        $result = $queries->buildQuery($query, $request);

        return new ChapterCollection($result['query']
            ->orderBy('position', 'asc')
            ->get());
    }

    /**
     * Affiche un chapitre
     *
     * @param  \App\Chapter  $chapter
     * @return \Illuminate\Http\Response
     */
    public function show(Chapter $chapter)
    {
        $this->enseignementChecker->checkReadInactive($chapter, $chapter->teacher);

        $this->loadDependences($chapter);

        return new ChapterResource($chapter);
    }


    /**
     * Créer un chapitre
     *
     * @param \App\Http\Requests\ChapterRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ChapterRequest $request, SyncOption $syncOption)
    {
        //
        $chapter = new Chapter(array_merge($this->extractChapterFields($request), $this->extractEnseignementFields($request)));

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

        if ($request->has('position')) {
            $chapter->position = $request->get('position');
        } else {
            $chapter->position = isset($lastChapter) ? $lastChapter->position + 1 : 1;
        }

        $chapter->save();

        $this->loadDependences($chapter);

        return new ChapterResource($chapter);
    }

    /**
     * Modification d'un chapitre
     *
     * @param  \Illuminate\Http\ChapterRequest  $request
     * @param  \App\Chapter  $chapter
     * @return \Illuminate\Http\Response
     */
    public function update(ChapterRequest $request, Chapter $chapter)
    {

        // Verifie que l'ut connecté peut modifier le chapitre
        $this->enseignementChecker->canUpdate($chapter);

        // extrait les champs à mettre à jour
        $fields = array_merge($this->extractChapterFields($request), $this->extractEnseignementFields($request));

        // Recupère la matiere pour verifier que le prof peut l'enseigner
        $matiere = isset($fields['matiere_id']) ? Matiere::findOrFail($fields['matiere_id']) : $chapter->matiere;

        // verifie que le teacher peut enseigner la matiere
        $this->teacherMatiereChecker->canTeach($chapter->teacher, $matiere, !isset($fields['active']));

        $chapter->update(collect($fields)->except('options')->all());

        $this->loadDependences($chapter);

        return $this->createdResponse(new ChapterResource($chapter));
    }

    /**
     * Affiche tous les exercices d'un chapitre
     */
    public function showExercises(Chapter $chapter)
    {
        $query = $chapter->exercises()->with(['type'])->orderBy('position', 'asc');

        // Le professeur peut lire les exercices qui ne sont pas activés
        if (!$this->userChecker->canReadInactive($chapter->teacher->user->username)) {
            $query = $query->where('active_enonce', 1);
        }

        return new ExerciseCollection($query->get());
    }

    /**
     * Affiche tous les exercices d'un chapitre
     */
    public function showQuestions(Chapter $chapter)
    {
        $query = $chapter->questions()->orderBy('position', 'asc');

        // Le professeur peut lire les exercices qui ne sont pas activés
        if (!$this->userChecker->canReadInactive($chapter->teacher->user->username)) {
            $query = $query->where('active', 1);
        }

        return new QuestionCollection($query->get());
    }

    // charge les dependences liées au professeur
    private function loadDependences(Chapter $chapter)
    {
        $chapter->load(['matiere', 'specialite', 'classe', 'teacher']);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Chapter  $chapter
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chapter $chapter)
    {
        // supprime le chapter
        $chapter->delete();
        return $this->deletedResponse();
    }
}
