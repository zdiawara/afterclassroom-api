<?php

namespace App\Http\Controllers\Api;


use App\Teacher;
use App\TeacherMatiere;
use App\Mail\UserIdentify;
use App\Constants\CodeReferentiel;
use Illuminate\Support\Facades\DB;
use App\Http\Actions\User\UserField;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Actions\User\ManageUser;
use App\Http\Requests\TeacherRequest;
use App\Http\Resources\TeacherResource;
use App\Http\Actions\Checker\UserChecker;
use App\Http\Actions\User\ManageIdentify;
use App\Http\Requests\ListTeacherRequest;
use Symfony\Component\HttpFoundation\Request;
use App\Http\Actions\Teacher\CountTeacherEnseignement;
use App\Http\Resources\WriterResource;

class TeacherController extends Controller
{

    private UserChecker $userChecker;

    public function __construct(UserChecker $userChecker, UserField $userField)
    {
        //$this->middleware('auth:api', ['except' => ['store']]);
        $this->userChecker = $userChecker;
        $this->userField = $userField;
    }

    /**
     * 
     */
    public function index(ListTeacherRequest $request)
    {
        $teachers = Teacher::whereHas('TeacherMatieres', function ($q) use ($request) {
            $q->where('matiere_id', $request->get('matiere'))
                ->where('etat_id', CodeReferentiel::VALIDATED)
                ->where(function ($query) use ($request) {
                    if ($request->has('level') && $request->get('level') === CodeReferentiel::LYCEE) {
                        $query->where('level_id', CodeReferentiel::LYCEE);
                    }
                })->whereHas('teacher', function ($query) use ($request) {
                    if ($request->has('search')) {
                        $search = strtolower($request->get('search'));
                        $query->whereHas('user', function ($q1)  use ($search) {
                            $q1->where(DB::raw('lower(username)'), 'like', '%' . $search . '%')
                                ->orWhere(DB::raw('lower(firstname)'), 'like', '%' . $search . '%')
                                ->orWhere(DB::raw('lower(lastname)'), 'like', '%' . $search . '%');
                        });
                    }
                });
        })->get();

        return TeacherResource::collection($teachers);
    }

    /**
     * 
     */
    public function store(TeacherRequest $request, ManageIdentify $manageIdentify, ManageUser $manageUser)
    {

        DB::beginTransaction();
        $username = $manageIdentify->buildIdentify();
        $teacher = new Teacher(['id' => $username]);
        $teacher->save();

        $teacher->user()->save($user = $manageUser->create($request, $username));

        collect($request->get('matieres'))->each(function ($matiere) use ($teacher) {
            $this->createTeacherMatiere($matiere, $teacher->id);
        });
        Mail::to($user->email)->queue(new UserIdentify($user));
        DB::commit();
        return $this->createdResponse(new TeacherResource($teacher));
    }

    private function createTeacherMatiere($matiere, $teacherId)
    {
        return TeacherMatiere::firstOrCreate(
            [
                'teacher_id' => $teacherId,
                'matiere_id' => $matiere['code'],
                'level_id' => $matiere['level']
            ],
            [
                'etat_id' => CodeReferentiel::VALIDATING
            ]
        );
    }

    public function update(TeacherRequest $request, Teacher $teacher, UserField $userField)
    {

        $this->userChecker->canUpdate($teacher);
        $teacher->user()->update($userField->extract($request));
        $teacher->load('user');
        return $this->createdResponse(new TeacherResource($teacher));
    }

    public function writers(Teacher $teacher)
    {
        return WriterResource::collection($teacher->writers()->get());
    }

    public function updateAvatar(Request $request, Teacher $teacher, ManageUser $manageUser)
    {
        $manageUser->updateAvatar($teacher->user, $request);
        return $this->createdResponse(new TeacherResource($teacher));
    }


    public function resume(Request $request, Teacher $teacher, CountTeacherEnseignement $countTeacherEnseignement)
    {
        return $this->createdResponse(new TeacherResource(
            Teacher::where('id', $teacher->id)->withCount($countTeacherEnseignement->execute($request))->first()
        ));
    }

    public function show(Teacher $teacher)
    {
        $teacher['teacher_matieres'] = TeacherMatiere::where('teacher_id', $teacher->id)
            ->with(['matiere.specialites', 'etat', 'level'])
            ->get();
        return new TeacherResource($teacher);
    }
}
