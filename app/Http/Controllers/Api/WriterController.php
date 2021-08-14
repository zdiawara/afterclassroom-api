<?php

namespace App\Http\Controllers\Api;


use App\Writer;
use App\TeacherMatiere;
use App\Mail\UserIdentify;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\WriterRequest;
use Illuminate\Support\Facades\Mail;
use App\Http\Actions\User\ManageUser;
use App\Http\Resources\WriterResource;
use App\Http\Resources\TeacherResource;
use App\Http\Requests\ListWriterRequest;
use App\Http\Actions\User\ManageIdentify;

class WriterController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['store']]);
    }

    /**
     * 
     */
    public function index(ListWriterRequest $request)
    {
        $writers = Writer::whereHas('user', function ($query) use ($request) {
            if ($request->has('username')) {
                $username = strtolower($request->get('username'));
                $query->where(DB::raw('lower(username)'), $username);
            }
        })->get();

        return WriterResource::collection($writers);
    }

    /**
     * 
     */
    public function store(WriterRequest $request, ManageIdentify $manageIdentify, ManageUser $manageUser)
    {

        DB::beginTransaction();
        $username = $manageIdentify->buildIdentify();
        $writer = new Writer(['id' => $username]);
        $writer->save();
        $writer->user()->save($user = $manageUser->create($request, $username));
        Mail::to($user->email)->queue(new UserIdentify($user));
        DB::commit();
        return $this->createdResponse(new WriterResource($writer));
    }

    public function teachers(Writer $writer)
    {
        $teachers = $writer->teachers()->get()
            ->map(function ($teacher) {
                $teacher['teacher_matieres'] = TeacherMatiere::where('teacher_id', $teacher->id)
                    ->with(['matiere.specialites', 'etat', 'level'])
                    ->get();
                return $teacher;
            });
        return TeacherResource::collection($teachers);
    }
}
