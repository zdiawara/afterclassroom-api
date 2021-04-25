<?php

namespace Tests\Feature\Chapter;

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

        factory(Chapter::class, $size)->make()->each(function () use ($teacher) {
            $this->createChapter($teacher);
        });

        $response = $this->actingAs($teacher->user)->get(route('chapters.index', [
            'teacher' => $teacher->user->username,
            'matiere' => 'test',
            'classe' => 'test'
        ]));

        $response->assertStatus(Response::HTTP_OK);
    }
}
