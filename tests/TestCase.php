<?php

namespace Tests;

use App\Book;
use App\User;
use App\Admin;
use App\Classe;
use App\Chapter;
use App\CollegeYear;
use App\Teacher;
use App\Controle;
use App\Exercise;
use App\Specialite;
use App\Referentiel;
use App\TeacherMatiere;
use App\Constants\CodeReferentiel;
use App\Constants\TypeReferentiel;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function decodeResource($resource)
    {
        return json_decode($resource->response()->getContent(), true);
    }
    protected function setUp(): void
    {
        parent::setUp();
        factory(CollegeYear::class)->create();
        collect([CodeReferentiel::BASIC, CodeReferentiel::EXAM_SUBJECT, CodeReferentiel::FAQ])
            ->each(function ($code) {
                Referentiel::create(
                    array_merge(
                        ["id" => $code, "type" => TypeReferentiel::ENSEIGNEMENT],
                        collect(factory(Referentiel::class)->make()->toArray())->except(['id', 'type'])->all()
                    )
                );
            });
    }

    protected function createAdmin()
    {
        $admin = new Admin();
        $admin->save();
        $admin->user()->save($user = factory(User::class)->make());
        return $admin;
    }

    protected function makeStudent(Classe $classe)
    {

        $user = factory(User::class)->make();
        return $this->post(
            route('students.store'),
            array_merge(
                ["classe" => $classe->id],
                $user->toArray()
            )
        );
    }

    protected function createStudent($size = 1)
    {
        $classe = factory(Classe::class)->create();
        if ($size == 1) {
            return $this->makeStudent($classe);
        }
        for ($i = 0; $i < $size; $i++) {
            $this->makeStudent($classe);
        }
    }

    protected function createTeacher()
    {
        $user = factory(User::class)->make();
        // $teacher = factory(Teacher::class)->make();

        $data = array_merge(
            factory(Teacher::class)->make()->toArray(),
            $user->toArray()
        );
        //$teacher->user = $user;
        $response = $this->post(route('teachers.store'), $data);
        $teacherCreated = User::where('email', $user->email)
            ->first()
            ->userable;
        TeacherMatiere::where('teacher_id', $teacherCreated->id)->update(['etat_id' =>  CodeReferentiel::VALIDATED]);

        return ['teacher' => $teacherCreated, 'response' => $response];
    }

    protected function createControle($t = null,  $classeId = null, $matiereId = null)
    {

        $this->createTeacher();

        $teacher = $t == null ? Teacher::first() : $t;

        $controle = factory(Controle::class)->make();

        $controle->classe = $classeId == null ? factory(Classe::class)->create()->id : $classeId;
        $controle->matiere = $matiereId == null ?  $teacher->matieres->random()->id : $matiereId;

        $specialite = Specialite::where('matiere_id', $controle->matiere)->first();

        $controle->specialite = isset($specialite) ? $specialite->id : null;

        $controle->teacher = $teacher->id;
        $response = $this->actingAs($teacher->user)
            ->post(route('controles.store'), $controle->toArray());

        return  [
            "controle" => $controle,
            "teacher" => $teacher,
            "response" => $response
        ];
    }

    protected function addEnseignementDeps($model, Teacher $teacher)
    {
        $classeId = factory(Classe::class)->create()->id;
        $matiereId = $teacher->matieres->random()->id;
        return [
            "classe" => $classeId,
            "matiere" => $matiereId
        ];
    }

    protected function createChapter($t = null, $classeId = null, $matiereId = null)
    {

        if ($t == null) {
            $this->createTeacher();
        }
        $teacher = $t == null ? Teacher::first() : $t;

        $chapter = factory(Chapter::class)->make();
        $chapter->teacher = $teacher->id;

        $chapter->classe = $classeId == null ? factory(Classe::class)->create()->id : $classeId;
        $chapter->matiere = $matiereId == null ? $teacher->matieres->random()->id : $matiereId;

        $data = array_merge(
            [
                "content" => [
                    "data" => $chapter->content,
                    "active" => $chapter->is_active
                ]
            ],
            collect($chapter->toArray())->except(['content', 'is_active'])
                ->all()
        );

        $response = $this->actingAs($teacher->user)->post(route('chapters.store'), $data);

        return  [
            "chapter" => Chapter::where('title', $chapter->title)->first(),
            "teacher" => $teacher,
            "response" => $response
        ];
    }

    protected function createExercise($_chapter = null)
    {
        $chapter = $_chapter == null ?  $this->createChapter()['chapter'] : $_chapter;

        $response = $this->actingAs($chapter->teacher->user)->post(
            route('exercises.store'),
            array_merge(
                factory(Exercise::class)->make()->toArray(),
                [
                    "chapter" => $chapter->id,
                ]
            )
        );

        return [
            'exercise' => $chapter->exercises()->first(),
            'response' => $response
        ];
    }
}
