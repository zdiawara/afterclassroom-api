<?php

namespace Tests\Feature\Chapter;

use Tests\TestCase;
use App\Http\Resources\ChapterResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;


class ShowChapterTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_teacher_can_read_her_chapter_content()
    {
        $this->withoutExceptionHandling();
        $result = $this->createChapter();
        $teacher = $result['teacher'];
        $chapter = $result['chapter'];
        $response = $this->actingAs($teacher->user)->get(route('chapters.show', [
            "chapter" => $chapter->id
        ]));

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJson($this->decodeResource(new ChapterResource($chapter)));
    }
}
