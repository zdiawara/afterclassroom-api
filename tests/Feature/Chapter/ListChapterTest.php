<?php

namespace Tests\Feature\Chapter;

use App\Classe;
use App\Chapter;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListChapterTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_user_can_list_teacher_chapters()
    {
        $this->withoutExceptionHandling();

        $teacher = $this->createTeacher()['teacher'];

        $size = 5;

        $classeId = factory(Classe::class)->create()->id;
        $matiereId =  $teacher->matieres->random()->id;

        factory(Chapter::class, $size)->make()->each(function () use ($teacher, $classeId, $matiereId) {
            $this->createChapter($teacher, $classeId, $matiereId);
        });

        $response = $this->actingAs($teacher->user)->get(route('chapters.index', [
            'teacher' => $teacher->id,
            'matiere' => $matiereId,
            'classe' => $classeId
        ]));

        $response->assertStatus(Response::HTTP_OK);

        $this->assertTrue(sizeof($response->json()['data']) == $size);
    }
}
