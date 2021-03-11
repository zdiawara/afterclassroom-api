<?php

namespace Tests\Feature\Chapter;

use App\Chapter;
use App\Matiere;
use App\Teacher;
use Tests\TestCase;
use App\Referentiel;
use App\Constants\CodeReferentiel;
use App\Constants\TypeReferentiel;
use App\Http\Resources\ChapterResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;


class ManageChapterTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_teacher_can_create_chapter()
    {
        $this->withoutExceptionHandling();
        
        $result = $this->createChapter();

        $result['response']
            ->assertStatus(Response::HTTP_CREATED);
            //->assertJson($this->decodeResource(new ChapterResource($result['chapter'])));
    }


    /** @test **/
    public function a_teacher_can_update_her_chapter()
    {
        $this->withoutExceptionHandling();

        $result = $this->createChapter();

        $chapter = Chapter::first();
        $chapter->title = 'titre 1';
        $chapter->active = 0;
        $chapter->resume = 'Resumé du chapitre';
        
        $response = $this->actingAs($result['teacher']->user)->put(
            route('chapters.update',["chapter"=>$chapter->id]),
            $chapter->toArray()
        );

        $response
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson($this->decodeResource(new ChapterResource($chapter)));
    }

    /** @test **/
    public function a_teacher_can_update_only_her_chapter()
    {

        $result = $this->createChapter();

        // Chapitre appartenant a un autre teacher
        $chapter = Chapter::first();
        $chapter->title = 'titre 1';

        $teacher = $this->createTeacher()['teacher'];

        $response = $this->actingAs($teacher->user)->put(
            route('chapters.update',["chapter"=>$chapter->id]),
            $chapter->toArray()
        );
        
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

    }


    /** @test **/
    public function privilege_exception_if_teacher_matiere_not_valide_on_creating()
    {
        //$this->withoutExceptionHandling();

        $teacher = $this->createTeacher()['teacher'];

        $chapter = factory(Chapter::class)->make();
        $this->addEnseignementDeps($chapter);
        
        $chapter->teacher = $teacher->id;

        $response = $this->actingAs($teacher->user)->post(
            route('chapters.store'),
            array_merge($chapter->toArray(),[
                'matiere' => [
                    'id' => $chapter->matiere,
                ],
                'classe' => [
                    'id' => $chapter->classe
                ]
            ])
        );
        
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

    }

    
    /** @test **/
    public function privilege_exception_if_teacher_matiere_not_valide_on_updating()
    {
        //$this->withoutExceptionHandling();

        $result = $this->createChapter();

        $matiere = factory(Matiere::class)->create();

        $result['teacher']->matieres()->attach($matiere->id,['etat_id' => \factory(Referentiel::class)->create()->id ]);
        $chapter = Chapter::first();
                
        $response = $this->actingAs($result['teacher']->user)->put(
            route('chapters.update',['chapter'=>$chapter->id]),
            [
                "matiere" =>[
                    'id' =>  $matiere->id
                ]
            ]
        );
        
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

    }


    /** @test **/
    public function a_chapter_field_can_be_update()
    {
        $result = $this->createChapter();

        $response = $this->actingAs($result['teacher']->user)->put(
            route('chapters.update',["chapter"=>Chapter::first()->id]),
            ['title'=>'new title']
        );

        $response->assertStatus(Response::HTTP_CREATED);
    }

}
