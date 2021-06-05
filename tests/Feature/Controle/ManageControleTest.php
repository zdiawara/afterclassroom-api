<?php

namespace Tests\Feature\Controle;

use App\Controle;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;


class ManageControleTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_teacher_can_create_controle()
    {
        $this->withoutExceptionHandling();

        $result = $this->createControle();

        $result['response']
            ->assertStatus(Response::HTTP_CREATED);
    }


    /** @test **/
    public function a_teacher_can_update_her_controle()
    {
        $this->withoutExceptionHandling();

        $result = $this->createControle();

        $controle = Controle::first();

        $response = $this->actingAs($result['teacher']->user)->put(
            route('controles.update', ["controle" => $controle->id]),
            [
                'enonce' => [
                    'data' => 'test',
                    'active' => 1
                ],
                'year' => 2019
            ]
        );

        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertTrue(Controle::first()->enonce == 'test');
    }

    /** @test **/
    public function a_teacher_can_update_only_her_controle()
    {

        //$this->withoutExceptionHandling();

        $result = $this->createControle();

        $controle = Controle::first();
        $controle->active = 0;

        $teacher = $this->createTeacher()['teacher'];

        $response = $this->actingAs($teacher->user)->put(
            route('controles.update', ["controle" => $controle->id]),
            ['year' => 2019]
        );

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }



    /** @test **/
    public function a_controle_field_can_be_update()
    {
        $result = $this->createControle();

        $response = $this->actingAs($result['teacher']->user)->put(
            route('controles.update', ["controle" => Controle::first()->id]),
            ['year' => 2020]
        );

        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertTrue(Controle::first()->year == 2020);
    }
}
