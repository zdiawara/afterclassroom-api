<?php

namespace App\Http\Controllers\Api;

use App\Matiere;
use App\Teacher;
use App\TeacherMatiere;
use Illuminate\Http\Request;
use App\Constants\CodeReferentiel;
use App\Http\Controllers\Controller;
use App\Http\Actions\File\UploadFile;

use App\Http\Actions\Checker\UserChecker;
use App\Http\Requests\TeacherMatiereRequest;
use App\Http\Resources\TeacherMatiereResource;
use App\Http\Resources\TeacherMatiereCollection;
use App\Http\Actions\Referentiel\FindReferentiel;
use App\Referentiel;

class TeacherMatiereController extends Controller
{
    private $userChecker;

    public function __construct(UserChecker $userChecker, UploadFile $uploadFile)
    {
        $this->middleware('auth:api');
        $this->middleware('role:teacher');
        $this->userChecker = $userChecker;
        $this->uploadFile = $uploadFile;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Teacher $teacher)
    {
        return new TeacherMatiereCollection(TeacherMatiere::where('teacher_id', $teacher->id)
            ->with(['matiere.specialites', 'etat', 'level'])
            ->get());
    }


    public function store(TeacherMatiereRequest $request, Teacher $teacher)
    {
        $matiereId = $request->get('matiere');

        $teacherMatiere = TeacherMatiere::withTrashed()->where('teacher_id', $teacher->id)
            ->where('matiere_id', $matiereId)
            ->first();

        if (!is_null($teacherMatiere) && !$teacherMatiere->trashed()) {
            return $this->conflictResponse("Vous enseignez déjà cette matière");
        }

        $teacherMatiere = TeacherMatiere::firstOrCreate(
            [
                'teacher_id' => $teacher->id,
                'matiere_id' => $matiereId,
            ],
            [
                'etat_id' => CodeReferentiel::VALIDATING
            ]
        );

        $teacherMatiere->load('matiere.specialites');
        $teacherMatiere->load('etat');

        return $this->createdResponse(new TeacherMatiereResource($teacherMatiere));
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
        //$this->userChecker->checkUpdateEnseignement($chapter);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Teacher $teacher, Matiere $matiere)
    {
        //$this->userChecker->canDeleteTeacherMatiere($teacher, $matiere);
        $teacher->matieres()->detach($matiere->id);
        return $this->deletedResponse();
    }
}
