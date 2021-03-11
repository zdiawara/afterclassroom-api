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
        
        \factory(Exercise::class,5)->make()->each(function ($exercise) use ($result){
            $response = $this->actingAs($result['teacher']->user)->post(
                route('chapters.exercises.store',["chapter"=>Chapter::first()->id]),
                $exercise->toArray()
            );
        });

        $response = $this->actingAs($result['teacher']->user)->get(
            route('chapters.exercises.index',["chapter"=>Chapter::first()->id])
        );

        $response->assertStatus(Response::HTTP_OK);

        $this->assertEquals(5,sizeof($response->json()['data']));
    }
}