<?php

namespace Tests;

use App\Book;
use App\User;
use App\Admin;
use App\Classe;
use App\Option;
use App\Chapter;
use App\Student;
use App\Teacher;
use App\Controle;
use App\Exercise;
use App\Specialite;
use App\Referentiel;
use App\MatiereTeacher;
use App\Constants\CodeReferentiel;
use App\Constants\TypeReferentiel;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function decodeResource($resource){
        return json_decode($resource->response()->getContent(), true);
    }

    protected function createAdmin(){
        $admin = new Admin();
        $admin->save();
        $admin->user()->save($user = factory(User::class)->make());
        return $admin;
    }

    protected function makeStudent(){
        $student = factory(Student::class)->make();
        $user = factory(User::class)->make();
        return $this->post(route('students.store'),
            array_merge(
                $student->toArray(),
                $user->toArray()
            )
        );
    }

    protected function createStudent($size=1){
        if($size==1){
            return $this->makeStudent();
        }
        for ($i=0; $i < $size; $i++) { 
            $this->makeStudent();
        }
    }

    protected function createTeacher(){
        $user = factory(User::class)->make();
        $teacher = factory(Teacher::class)->make();
        
        $teacher->user = $user;

        $response = $this->post(route('teachers.store'),array_merge(
            $user->toArray(),
            $teacher->toArray()
        ));

        $teacherCreated = User::where("firstname",$teacher->user->firstname)->where("lastname",$teacher->user->lastname)->first()->userable;

        MatiereTeacher::where('teacher_id',$teacherCreated->id)->update([
            'etat_id' =>  Referentiel::firstOrCreate(
                ["code" => CodeReferentiel::VALIDATED,"type" => TypeReferentiel::ETAT],
                collect(factory(Referentiel::class)->make()->toArray())->except(['code','type'])->all()
            )->id
        ]);
        
        return [
            'teacher' => $teacherCreated,
            'response' => $response
        ];
    }

    protected function createControle($t = null){

        $this->createTeacher();
        
        $teacher = $t==null ? Teacher::first() : $t;

        $controle = factory(Controle::class)->make();

        $this->addEnseignementDeps($controle);

        $matiereId = $teacher->matieres->first()->id;
        
        $Specialite = Specialite::where('matiere_id',$matiereId)->first();
        
        $controle->matiere = [
            'id' => $matiereId,
            'specialite' => isset($Specialite) ? $Specialite->id : null
        ];

        $controle->classe = [
            'id' => $controle->classe,
            'option' =>  Option::where('classe_id',$controle->classe)->first()->id
        ];

        $controle->teacher = $teacher->id;

        $controle->type = Referentiel::firstOrCreate(['code' => CodeReferentiel::DEVOIR, 'type' => TypeReferentiel::CONTROLE],
        ['name' => "Test"])->code;

        $controle->trimestre = Referentiel::firstOrCreate(['code' => CodeReferentiel::TRIMESTRE_1, 'type' => TypeReferentiel::TRIMESTRE],
        ['name' => "Test"])->code;

        $response = $this->actingAs($teacher->user)
            ->post(route('controles.store'),$controle->toArray());

        return  [
            "controle" => $controle,
            "teacher" => $teacher,
            "response" => $response
        ];
    }

    protected function createBook($t = null){

        if($t==null){
            $this->createTeacher();
        }
        $teacher = $t==null ? Teacher::first() : $t;

        $book = factory(Book::class)->make();

        $classe = factory(Classe::class)->create();
        $specialite = factory(Specialite::class)->create();
        
        $book->classes = [$classe->code];
        $book->matiere = $specialite->matiere->code;
        
        $matiere = $teacher->matieres->first();
        
        $specialite = Specialite::where('matiere_id',$matiere->id)->first();
        
        $book->matiere = [
            'code' => $matiere->code,
            'specialite' => isset($specialite) ? $specialite->code : null
        ];

        $book->teacher = $teacher->user->username;
                
        $response = $this->actingAs($teacher->user)
            ->post(route('books.store'),$book->toArray());

        return  [
            "book" => $book,
            "teacher" => $teacher,
            "response" => $response
        ];
    }

    protected function addEnseignementDeps($model){
        $classe = factory(Classe::class)->create();
    
        $specialite = factory(Specialite::class)->create();
        
        $model->classe = $classe->code;
        $model->matiere = $specialite->matiere->code;
    
        $option = factory(Option::class)->make();
        $option->classe_id = $classe->id;
        $option->save(); 
    }

    protected function createChapter($t = null){

        if($t==null){
            $this->createTeacher();
        }
        $teacher = $t==null ? Teacher::first() : $t;

        $chapter = factory(Chapter::class)->make();
        $this->addEnseignementDeps($chapter);

        $matiere = $teacher->matieres->first();
        
        $specialite = Specialite::where('matiere_id',$matiere->id)->first();
        
        $chapter->matiere = [
            'code' => $matiere->code,
            'specialite' => isset($specialite) ? $specialite->code : null
        ];

        $chapter->classe = [
            'code' => $chapter->classe,
            'options' => [
                Option::where('classe_id',$chapter->classe)->first()->code
            ]
        ];

        $chapter->teacher = $teacher->user->username;
        
        $response = $this->actingAs($teacher->user)
            ->post(route('chapters.store'),$chapter->toArray());

        return  [
            "chapter" => Chapter::where('title',$chapter->title)->first(),
            "teacher" => $teacher,
            "response" => $response
        ];
    }

    protected function createExercise(){
        $chapter = $this->createChapter()['chapter'];
        $exercise = factory(Exercise::class)->make();

        $type = Referentiel::firstOrCreate(['code' => CodeReferentiel::APPLICATION,'type' => TypeReferentiel::EXERCISE],['name'=>'test']);

        $response = $this->actingAs($chapter->teacher->user)->post(route('exercises.store'),
            array_merge(
                factory(Exercise::class)->make()->toArray(),
                ["chapter" => $chapter->id, "type" => $type->id]
            )
        );
        
        return [
            'exercise' => $chapter->exercises()->first(),
            'response' => $response
        ];
    }
}
