<?php

namespace App\Http\Controllers\Api;

use App\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\BookCategoryRequest;
use App\Http\Actions\Checker\EnseignementChecker;
use App\Http\Actions\Checker\TeacherMatiereChecker;

class BookCategoryController extends Controller
{

    private $enseignementChecker;

    private $teacherMatiereChecker;

    public function __construct(EnseignementChecker $enseignementChecker,TeacherMatiereChecker $teacherMatiereChecker)
    {
        $this->middleware(['auth:api']);
        $this->middleware('role:teacher',['except' => ['index','show']]);
        $this->enseignementChecker = $enseignementChecker;
        $this->teacherMatiereChecker = $teacherMatiereChecker;
    }
    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookCategoryRequest $request, Book $book)
    {
        //
        $this->enseignementChecker->canCreate($book);
        $this->teacherMatiereChecker->canEdit($book->teacher,$book->matiere);
        $book->categories()->sync(
            \collect($request->get('categories'))->map(function($category){
                return $category['id'];
            })
        );

        return $this->createdResponse(['categories' => CategoryResource::collection($book->categories)]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
