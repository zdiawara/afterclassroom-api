<?php

namespace Tests\Feature\Exercise;

use Tests\TestCase;
use App\Referentiel;
use App\Constants\CodeReferentiel;
use App\Constants\TypeReferentiel;
use App\Exercise;
use App\Http\Resources\ExerciseResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageExerciseTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_teacher_can_add_exercise_to_chapter()
    {
        $this->withoutExceptionHandling();

        $result = $this->createExercise();

        $result['response']
            ->assertStatus(Response::HTTP_CREATED);
    }

    /** @test **/
    public function a_teacher_can_update_exercise()
    {
        $this->withoutExceptionHandling();

        $result = $this->createExercise();

        $exercise = $result['exercise'];
        $exercise->enonce = "Test enonce";
        $exercise->is_enonce_active = 0;
        $exercise->correction = "Test correction";

        $response = $this->actingAs($exercise->chapter->teacher->user)->put(
            route('exercises.update', ["exercise" => $exercise->id]),
            [

                "enonce" => [
                    'data' =>  $exercise->enonce,
                    'active' =>  $exercise->is_enonce_active
                ],
                "correction" => [
                    "data" => $exercise->correction,
                ]
            ]
        );

        $response
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson($this->decodeResource(new ExerciseResource($exercise)));
    }

    /** @test **/
    public function active_exercise()
    {
        $this->withoutExceptionHandling();

        $result = $this->createExercise();

        $response = $this->actingAs($result['exercise']->chapter->teacher->user)
            ->put(
                route('exercises.update', ["exercise" => $result['exercise']->id]),
                [
                    "enonce" => [
                        "active" => 0
                    ]
                ]
            );


        $response
            ->assertStatus(Response::HTTP_CREATED);
        $this->assertEquals(Exercise::find($result['exercise']->id)->is_active, 0);
    }
}
