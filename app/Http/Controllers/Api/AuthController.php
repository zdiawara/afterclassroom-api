<?php

namespace App\Http\Controllers\Api;

use App\Classe;
use App\Matiere;
use App\CollegeYear;
use App\MatiereTeacher;
use App\StudentTeacher;
use App\ClasseMatiere;
use App\Constants\CodeReferentiel;
use App\Http\Actions\Classe\ListClasseMatiere;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Actions\User\UserDetail;
use App\Http\Resources\StudentResource;
use App\Http\Resources\TeacherResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Actions\Student\ListStudentTeacher;

class AuthController extends Controller
{
    private $listStudentTeacher;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(ListStudentTeacher $listStudentTeacher)
    {
        $this->middleware('auth:api', ['except' => ['login']]);
        $this->listStudentTeacher = $listStudentTeacher;
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['username', 'password']);

        if (isset($credentials['username'])) {
            $credentials['username'] = strtoupper($credentials['username']);
        }
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['message' => 'Vos identifiants sont incorrects.'], Response::HTTP_UNAUTHORIZED);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(ListClasseMatiere $listClasseMatiere)
    {
        $user = auth()->userOrFail();
        $user->load('gender');

        if ($user->isTeacher()) {
            $teacher = $user->userable;
            $teacher['teacher_matieres'] = MatiereTeacher::where('teacher_id', $teacher->id)
                ->with(['matiere.specialites', 'etat', 'level'])
                ->get();

            return new TeacherResource($teacher);
        }
        if ($user->isStudent()) {
            $student = $user->userable;
            $student->load(['classe.level']);
            $student['matieres'] = $listClasseMatiere->byClasse($student->classe);

            $student['classes'] = Classe::with(['level'])->where('position', '<=', $student->classe->position)->get();
            $student['student_teachers'] = $this->listStudentTeacher->execute($student);
            return new StudentResource($student);
        }
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {

        //dd(auth()->factory()->getTTL());
        return response()->json([
            'accessToken' => $token,
            'tokenType' => 'bearer',
            'expiresIn' => auth()->factory()->getTTL()
        ]);
    }
}
