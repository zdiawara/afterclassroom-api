<?php

namespace Tests\Feature\Controle;

use App\Classe;
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

        $classeId = factory(Classe::class)->create()->id;
        $matiereId =  $teacher->matieres->random()->id;

        factory(Controle::class, $size)->make()->each(function () use ($teacher, $classeId, $matiereId) {
            $this->createControle($teacher, $classeId, $matiereId);
        });

        $response = $this->actingAs($teacher->user)->get(route('controles.index', [
            'teacher' => $teacher->id,
            'type' => CodeReferentiel::DEVOIR,
            'matiere' => $matiereId,
            'classe' => $classeId,
            'trimestre' => CodeReferentiel::TRIMESTRE_1
        ]));

        $response->assertStatus(Response::HTTP_OK);

        $this->assertTrue(sizeof($response->json()['data']) == $size);
    }
}
