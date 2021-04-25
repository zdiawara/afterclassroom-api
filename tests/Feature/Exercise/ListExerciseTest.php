<?php

namespace Tests\Feature\Exercise;

use App\Chapter;

use App\Exercise;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListExerciseTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function show_chapter_exercises()
    {
        $this->withoutExceptionHandling();
        $result = $this->createChapter();
        $SIZE = 5;
        factory(Exercise::class, $SIZE)->make()->each(function () use ($result) {
            $this->createExercise($result['chapter']);
        });
        $response = $this->actingAs($result['teacher']->user)->get(
            route('chapters.showExercises', ["chapter" => Chapter::first()->id])
        );
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals($SIZE, sizeof($response->json()['data']));
    }
}
