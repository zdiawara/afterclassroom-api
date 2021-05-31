<?php

namespace App\Http\Controllers\Api;

use App\Student;
use App\CollegeYear;
use App\Mail\UserIdentify;
use App\Constants\CodeReferentiel;
use Illuminate\Support\Facades\DB;
use App\Http\Actions\User\UserField;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Actions\User\ManageUser;
use App\Http\Requests\StudentRequest;
use App\Http\Resources\StudentResource;
use App\Http\Actions\Checker\UserChecker;
use App\Http\Actions\User\ManageIdentify;
use App\Http\Actions\Classe\ListClasseMatiere;

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
        // if ($request->has('classe')) {
        //     $fields['classe_id'] = $request->get('classe');
        // }
        return $fields;
    }

    private function loadDependences(Student $student)
    {
        $student->load(['user']);
    }


    public function store(StudentRequest $request,  ManageUser $manageUser,  ManageIdentify $manageIdentify)
    {

        DB::beginTransaction();

        $username = $manageIdentify->buildIdentify();

        $student = new Student(['id' => $username]);
        $student->save();
        $student->user()->save($user = $manageUser->create($request, $username));

        $student->classes()->attach($request->get('classe'), [
            'college_year_id' => CollegeYear::where('etat_id', CodeReferentiel::IN_PROGRESS)
                ->firstOrFail()->id,
            'changed' => 1
        ]);

        Mail::to($user->email)->queue(new UserIdentify($user));

        DB::commit();

        $this->loadDependences($student);

        return $this->createdResponse(new StudentResource($student));
    }

    public function show($id)
    {
        //
    }

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

    public function destroy($id)
    {
        //
    }
}
