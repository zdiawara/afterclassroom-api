<?php

namespace App\Http\Controllers\Api;

use App\Classe;
use App\Teacher;
use App\MatiereTeacher;
use App\Mail\UserIdentify;
use App\Constants\CodeReferentiel;
use App\Http\Actions\User\UserField;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Actions\User\ManageUser;
use App\Http\Requests\TeacherRequest;
use App\Http\Resources\TeacherResource;
use App\Http\Actions\Checker\UserChecker;
use Symfony\Component\HttpFoundation\Request;
use App\Http\Actions\Referentiel\FindReferentiel;
use App\Http\Actions\Teacher\CountTeacherEnseignement;
use App\Http\Requests\ListTeacherRequest;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Support\Facades\DB;


class TeacherController extends Controller
{

    private UserChecker $userChecker;
    private FindReferentiel $findReferentiel;

    public function __construct(UserChecker $userChecker, UserField $userField, FindReferentiel $findReferentiel)
    {
        $this->middleware('auth:api', ['except' => ['store']]);
        $this->userChecker = $userChecker;
        $this->userField = $userField;
        $this->findReferentiel = $findReferentiel;
    }

    /**
     * 
     */
    public function index(ListTeacherRequest $request)
    {
        $teachers = Teacher::whereHas('matiereTeachers', function ($q) use ($request) {
            $q->whereHas('matiere', function ($query) use ($request) {
                $query->where('code', $request->get('matiere'));
            })->whereHas('level', function ($query) use ($request) {
                if ($request->has('level') && $request->get('level') === CodeReferentiel::LYCEE) {
                    $query->where('code', CodeReferentiel::LYCEE);
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
            })->whereHas('etat', function ($query) {
                $query->where('code', CodeReferentiel::VALIDATED);
            });
        })->get();

        return TeacherResource::collection($teachers);
    }

    /**
     * 
     */
    public function store(TeacherRequest $request, ManageUser $manageUser, FindReferentiel $findReferentiel)
    {
        DB::beginTransaction();
        $teacher = new Teacher();
        $teacher->save();
        $teacher->user()->save($user = $manageUser->create($request));
        collect($request->get('matieres'))->each(function ($matiere) use ($teacher) {
            $this->createTeacherMatiere($matiere, $teacher->id);
        });
        Mail::to($user->email)->queue(new UserIdentify($user));
        DB::commit();
        return $this->createdResponse(new TeacherResource($teacher));
    }

    private function createTeacherMatiere($matiere, $teacherId)
    {
        return MatiereTeacher::firstOrCreate(
            [
                'teacher_id' => $teacherId,
                'matiere_id' => $matiere['code'],
                'level_id' => $matiere['level']
            ],
            [
                'etat_id' => $this->findReferentiel->byCodeEtat(CodeReferentiel::VALIDATING)->id
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
}
