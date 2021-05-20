<?php

namespace Tests\Feature\Controle;

use App\Teacher;
use App\Controle;
use Tests\TestCase;
use App\Constants\CodeReferentiel;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListControleTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_user_can_list_teacher_controles()
    {
        $this->withoutExceptionHandling();
        
        $teacher = $this->createTeacher()['teacher'];
        
        $size = 5;

        factory(Controle::class,$size)->make()->each(function($controle) use ($teacher){
            $this->createControle($teacher);
        });

        $response = $this->actingAs($teacher->user)->get(route('controles.index', [
            'teacher' => $teacher->user->username,
            'type' => CodeReferentiel::DEVOIR
        ]));

        $response->assertStatus(Response::HTTP_OK);
        
        $this->assertTrue($response->json()['meta']['total']==$size);
    }

}
