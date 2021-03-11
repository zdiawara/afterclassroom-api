<?php

namespace Tests\Feature\Chapter;

use App\Chapter;
use App\Teacher;
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
        
        $teacher = factory(Teacher::class)->create();
        
        $size = 5;

        factory(Chapter::class,$size)->make()->each(function($chapter) use ($teacher){
            $this->createChapter($teacher);
        });

        $response = $this->actingAs($teacher->user)->get(route('chapters.index', [
            'teacher' => $teacher->user->username
        ]));

        $response->assertStatus(Response::HTTP_OK);
        
        //$this->assertTrue($response->json()['total']==$size);
    }

}
