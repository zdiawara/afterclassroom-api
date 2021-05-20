<?php

namespace App\Http\Actions\User;

use App\MatiereTeacher;
use App\Http\Resources\TeacherResource;


class UserDetail{

    public function auth(){
        $user = auth()->userOrFail();
        if($user->isTeacher()){
            $teacherMatieres = MatiereTeacher::where('teacher_id',$user->userable->id)
                ->with(['matiere','etat'])
                ->get();
            
            dd(new TeacherResource($user->userable));
        }
    }
}