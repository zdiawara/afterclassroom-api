<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MailController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    
    
    /**
     * 
     */
    public function send(Request $request){
                        
        Mail::to($user->email)->queue(new UserIdentify($user));
        
        return \response()->json([
            "message" => "Mail envoyé avec succès !"
        ]);
    }

    public function update(TeacherRequest $request, Teacher $teacher, UserField $userField){
        
        $this->userChecker->canUpdate($teacher);
        $teacher->user()->update($userField->extract($request));
        $teacher->load('user');
        return $this->createdResponse(new TeacherResource($teacher));
    }

    public function updateAvatar(Request $request, Teacher $teacher, ManageUser $manageUser)
    {
        $manageUser->updateAvatar($teacher->user,$request);   
        return $this->createdResponse(new TeacherResource($teacher));
    }


    public function resume(Request $request,Teacher $teacher, CountTeacherEnseignement $countTeacherEnseignement){       
        return $this->createdResponse(new TeacherResource(
            Teacher::where('id',$teacher->id)->withCount($countTeacherEnseignement->execute($request))->first()
        ));
    }
}
