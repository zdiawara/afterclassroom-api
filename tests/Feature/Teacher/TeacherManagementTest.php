<?php

namespace Tests\Feature\Teacher;

use App\User;
use App\Teacher;
use Tests\TestCase;
use App\Referentiel;
use App\Http\Resources\TeacherResource;
use App\Http\Resources\ReferentielResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeacherManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_teacher_can_be_create()
    {
        $this->withoutExceptionHandling();

        $result = $this->createTeacher();
        
        $result['response']
            ->assertStatus(Response::HTTP_CREATED);
            //->assertJsonFragment($this->decodeResource(new TeacherResource($teacher)));
    }


    /** @test **/
    public function a_teacher_can_update_her_profil()
    {
        //$this->withoutExceptionHandling();

        $result = $this->createTeacher();
        
        $teacher = Teacher::First();
               
        $response = $this->actingAs($teacher->user)->put(
            route('teachers.update',["teacher"=>$teacher->id]),
            [
                "firstname" => "Prenom 1",
                "username" => 'zdiawara'
            ]
        );

        $response
            ->assertStatus(Response::HTTP_CREATED);
            //->assertJsonFragment($this->decodeResource(new TeacherResource($teacher)));
        $this->assertTrue(Teacher::first()->user->firstname==="Prenom 1");
    }

    /** @test **/
    public function a_teacher_can_not_update_an_other_profil()
    {
        //$this->withoutExceptionHandling();

        $teacher = factory(Teacher::class)->create();               
        
        $response = $this->actingAs(factory(Teacher::class)->create()->user)->put(
            route('teachers.update',["teacher"=>$teacher->id]),
            ["lastname"=>"lastname 1"]
        );

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    
    }


}
