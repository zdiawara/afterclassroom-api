<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Student;
use App\Mail\UserIdentify;
use Illuminate\Http\Request;
use App\Http\Actions\User\UserField;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Actions\User\ManageUser;
use App\Http\Requests\StudentRequest;
use App\Http\Resources\StudentResource;
use App\Http\Actions\Checker\UserChecker;
use App\Http\Actions\Classe\ListClasseMatiere;
use Illuminate\Support\Facades\DB;


class StudentController extends Controller
{
    private $userChecker;
    private $userField;

    public function __construct(UserChecker $userChecker, UserField $userField)
    {
        $this->middleware('auth:api', ['except' => ['store']]);
        $this->userChecker = $userChecker;
        $this->userField = $userField;
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

    private function extractStudentFields($request)
    {
        $fields = [];
        if ($request->has('classe')) {
            $fields['classe_id'] = $request->get('classe');
        }
        return $fields;
    }

    private function loadDependences(Student $student)
    {
        $student->load(['classe.level', 'user']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StudentRequest $request,  ManageUser $manageUser)
    {
        $student = new Student($this->extractStudentFields($request));

        DB::beginTransaction();

        $student->save();

        $student->user()->save($user = $manageUser->create($request));

        Mail::to($user->email)->queue(new UserIdentify($user));

        DB::commit();

        $this->loadDependences($student);

        return $this->createdResponse(new StudentResource($student));
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
    public function update(StudentRequest $request, Student $student, ListClasseMatiere $listClasseMatiere)
    {
        //
        DB::beginTransaction();
        $this->userChecker->canUpdate($student);

        $student->update($this->extractStudentFields($request));

        $student->user()->update($this->userField->extract($request));
        DB::commit();
        $this->loadDependences($student);

        if ($request->has("classe")) {
            $student["matieres"] = $listClasseMatiere->byClasse($student->classe);
        }

        return $this->createdResponse(new StudentResource($student));
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
