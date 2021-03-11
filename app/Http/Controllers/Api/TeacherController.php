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
use Illuminate\Support\Facades\DB;


class TeacherController extends Controller
{

    private $userChecker;

    public function __construct(UserChecker $userChecker, UserField $userField)
    {
        $this->middleware('auth:api', ['except' => ['store']]);
        $this->userChecker = $userChecker;
        $this->userField = $userField;
    }

    /**
     * 
     */
    public function index(Request $request)
    {
        $matiere = $request->get('matiere');
        $query = MatiereTeacher::whereHas('matiere', function ($q) use ($matiere) {
            $q->where('code', $matiere);
        })->whereHas('etat', function ($q) {
            $q->where('code', CodeReferentiel::VALIDATED);
        });

        $level = $request->has('classe')
            ? Classe::where('code', $request->get('classe'))
            ->with('level')
            ->firstOrFail()
            ->level->code
            : $request->get('level');

        if (isset($level) && $level === CodeReferentiel::LYCEE) {
            $query = $query->whereHas('level', function ($q) {
                $q->where('code', CodeReferentiel::LYCEE); 
            });
        }
        
        $ids = $query->get()->map(function ($matiereTeacher) {
            return $matiereTeacher->teacher_id;
        })->all();

        return TeacherResource::collection(Teacher::whereIn('id', $ids)->get());
    }

    /**
     * 
     */
    public function store(TeacherRequest $request, ManageUser $manageUser, FindReferentiel $findReferentiel)
    {

        $teacher = new Teacher();

        DB::beginTransaction();

        $teacher->save();

        $teacher->user()->save($user = $manageUser->create($request));

        collect($request->get('matieres'))->each(function ($matiere) use ($teacher, $findReferentiel) {
            MatiereTeacher::firstOrCreate(
                [
                    'teacher_id' => $teacher->id,
                    'matiere_id' => $matiere['code'],
                    'level_id' => $matiere['level']
                ],
                [
                    'etat_id' => $findReferentiel->byCodeEtat(CodeReferentiel::VALIDATING)->id
                ]
            );
        });

        Mail::to($user->email)->queue(new UserIdentify($user));

        DB::commit();

        return $this->createdResponse(new TeacherResource($teacher));
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
