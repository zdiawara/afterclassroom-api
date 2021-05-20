<?php

namespace Tests\Feature\Exercise;


use App\Chapter;
use App\Exercise;
use Tests\TestCase;
use App\Referentiel;
use App\Constants\CodeReferentiel;
use App\Constants\TypeReferentiel;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageQuestionTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_teacher_can_add_question_to_chapter()
    {
        /* $this->withoutExceptionHandling();

        $result = $this->createExercise();

        $result['response']
            ->assertStatus(Response::HTTP_CREATED); */
    }

    /** @test **/
    public function a_teacher_can_update_question()
    {
        /* $this->withoutExceptionHandling();

        $result = $this->createExercise();

        $exercise = $result['exercise'];

        $type = Referentiel::firstOrCreate([
            'code' => CodeReferentiel::SYNTHESE,
            'type' => TypeReferentiel::EXERCISE
        ], ['name' => 'Test',]);

        $response = $this->actingAs($exercise->chapter->teacher->user)->put(
            route('exercises.update', ["exercise" => $exercise->id]),
            [
                "type" => $type->id,
                "enonce" => [
                    'data' => 'enoncé',
                    'active' => '0'
                ]
            ]
        );

        $exercise = Exercise::first();
        $this->assertTrue($type->id == $exercise->type->id); */
    }

    /** @test **/
    public function active_question()
    {
        $this->withoutExceptionHandling();

        /* $result = $this->createExerciseForChapter();

        $response = $this->actingAs($result['teacher']->user)->put(
            route('exercises.update', ["exercise" => $result['exercise']->id]),
            ["active" => 1]
        );

        $exercise = Exercise::first();
        $this->assertTrue($exercise->active == 1);
        $this->assertTrue($exercise->content->active == 1);
        $this->assertTrue($exercise->solution->content->active == 1); */
    }
}
