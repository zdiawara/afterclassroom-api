<?php

namespace App\Http\Controllers\Api;

use App\Book;
use App\Matiere;
use App\Http\Actions\Queries;
use App\Http\Requests\BookRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Http\Actions\File\UploadFile;
use App\Http\Resources\BookCollection;
use App\Http\Actions\Sync;
use App\Http\Resources\ContentResource;
use App\Http\Actions\Content\ManageContent;
use Symfony\Component\HttpFoundation\Request;
use App\Http\Actions\Checker\EnseignementChecker;
use App\Http\Actions\Checker\TeacherMatiereChecker;

class BookController extends Controller
{
    private $enseignementChecker;

    private $teacherMatiereChecker;

    private $uploadFile;

    public function __construct(UploadFile $uploadFile, EnseignementChecker $enseignementChecker,TeacherMatiereChecker $teacherMatiereChecker)
    {
        $this->middleware(['auth:api']);
        $this->middleware('role:teacher',['except' => ['index','show']]);
        $this->enseignementChecker = $enseignementChecker;
        $this->teacherMatiereChecker = $teacherMatiereChecker;
        $this->uploadFile = $uploadFile;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Queries $queries)
    {
        //
        $teacher = $request->get('teacher');

        $query = Book::whereHas('teacher.user',function($q) use ($teacher){
            $q->where('username',$teacher);
        });

        $result = $queries->bookQuery($query,$request);

        return new BookCollection($result['query']->paginate(9,['*'], 'page', $result['page']));
    }

    private function extractBookFields($request){
        return array_merge(
            $request->only(['title','resume','active','price']),
            $this->extractContent($request)
        );
    }

    private function extractBookDependances($request){
        return array_merge(
            $this->extractMatiere($request),
            $this->extractClasses($request),
            $this->extractTeacher($request)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookRequest $request, Sync $sync, ManageContent $manageContent)
    {

        $fields = $this->extractBookDependances($request);

        $book = new Book(array_merge(
            $this->extractBookFields($request),
            collect($fields)->except(['classes'])->all()
        ));

        // Verifie que l'ut peut crée le livre
        $this->enseignementChecker->canCreate($book);
        
        // verifie que le teacher peut enseigner la matiere
        $this->teacherMatiereChecker->canTeach($book->teacher,$book->matiere,true);
        
        if($request->has('cover')){
            $book->cover = $this->uploadFile->image($request->file('cover') );
        }else{
            $book->cover = 'cover.png';
        }
                
        $book->save();

        $sync->syncClasses($book,$fields);
                
        return $this->createdResponse(new BookResource($book));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        //
        $this->loadDependences($book);
        return new BookResource($book);
    }

    private function loadDependences(Book $book){
        $book->load(['matiere','specialite','teacher','classes']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BookRequest $request, Book $book, Sync $sync)
    {
        // Verifie que l'ut connecté peut créer modifier le livre
        $this->enseignementChecker->canUpdate($book);

        $fields = array_merge($this->extractBookFields($request),
        collect($this->extractBookDependances($request))->except(['classes'])->all());


        // Recupère la matiere pour verifier que le prof peut l'enseigner
        $matiere = isset($fields['matiere_id']) ? Matiere::find($fields['matiere_id']) : $book->matiere;
        
        // verifie que le teacher peut enseigner la matiere
        $this->teacherMatiereChecker->canTeach($book->teacher,$matiere, !isset($fields['active']));

        $book->update(collect($fields)->except(['classes'])->all()); 

        $sync->syncClasses($book,$fields);

        $this->loadDependences($book);
        
        return $this->createdResponse(new BookResource($book));
    }

    /**
     * Affiche le contenu du livre
     */
    public function showContent(Book $book){
        return new ContentResource($book->content);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCover(Request $request, Book $book)
    {
        
        if($request->file('cover')){
            $cover = $this->uploadFile->image($request->file('cover') );
            if(isset($cover)){
                $book->update(['cover' => $cover]);
            }
        }
        
        return $this->createdResponse(new BookResource($book));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        // TODO : verifie que le livre n'est pas vendu

        // supprime association classes
        $book->classes()->detach();

        // supprime le livre
        $book->delete();

        return $this->deletedResponse();

    }
}
