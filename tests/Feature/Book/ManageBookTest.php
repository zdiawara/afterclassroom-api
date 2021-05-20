<?php

namespace Tests\Feature\Chapter;

use App\Book;
use App\Chapter;
use App\Matiere;
use App\Teacher;
use App\Category;
use Tests\TestCase;
use App\Referentiel;
use App\Constants\CodeReferentiel;
use App\Constants\TypeReferentiel;
use App\Http\Resources\ChapterResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;


class ManageBookTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_teacher_can_create_book()
    {
        $this->withoutExceptionHandling();

        $result = $this->createBook();

        $result['response']
            ->assertStatus(Response::HTTP_CREATED);
        $book = Book::first();
        $this->assertNotNull($book);
        $this->assertTrue(\sizeof($book->classes) == 1);
    }


    /** @test **/
    public function a_teacher_can_update_her_book()
    {
        $this->withoutExceptionHandling();

        $result = $this->createBook();

        $book = Book::first();

        $fields = [
            "title" => "titre 1"
        ];
        $response = $this->actingAs($result['teacher']->user)->put(
            route('books.update', ["book" => $book->id]),
            $fields
        );

        $response->assertStatus(Response::HTTP_CREATED);

        $book = Book::first();
        $this->assertTrue($book->title == $fields['title']);
    }
}
