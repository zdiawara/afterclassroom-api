<?php

namespace Tests\Feature\Chapter;

use App\Book;
use App\Chapter;
use App\Teacher;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListBookTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_user_can_list_teacher_books()
    {
        $this->withoutExceptionHandling();
        
        $teacher = factory(Teacher::class)->create();
        
        $size = 5;

        factory(Book::class,$size)->make()->each(function($book) use ($teacher){
            $this->createBook($teacher);
        });

        $response = $this->actingAs($teacher->user)->get(route('books.index', [
            'teacher' => $teacher->user->username
        ]));

        $book = Book::first();
        
        $response->assertStatus(Response::HTTP_OK);
        
        //$this->assertTrue($response->json()['total']==$size);
    }

}
