<?php

namespace Tests\Feature\Chapter;

use App\Chapter;
use DOMDocument;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateContentTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_teacher_can_update_content()
    {
        $this->withoutExceptionHandling();
        
        $result = $this->createChapter();
        
        
        $response = $this->actingAs($result['teacher']->user)->put(
            route('contents.update', ['content' => Chapter::first()->content->id]),
            [
                'data' => 'Test'
            ]
        );

        $response->assertStatus(Response::HTTP_CREATED);

        $content = Chapter::first()->content;
        $this->assertEquals($content->data,"Test");
        
        //$this->assertTrue($response->json()['total']==$size);
        
    }
}
