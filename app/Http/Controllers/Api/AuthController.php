<?php

namespace App\Http\Controllers\Api;

use App\TeacherMatiere;
use App\Http\Controllers\Controller;
use App\Http\Resources\StudentResource;
use App\Http\Resources\TeacherResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Actions\Student\StudentDetail;

class AuthController extends Controller
{

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
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
    public function me(StudentDetail $studentDetail)
    {
        $user = auth()->userOrFail();
        $user->load('gender');

        if ($user->isTeacher()) {
            $teacher = $user->userable;
            $teacher['teacher_matieres'] = TeacherMatiere::where('teacher_id', $teacher->id)
                ->with(['matiere.specialites', 'etat', 'level'])
                ->get();

            return new TeacherResource($teacher);
        }
        if ($user->isStudent()) {
            return new StudentResource($studentDetail->execute($user->userable));
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
