<?php

namespace App\Http\Actions\User;


class UserField{

    public function extract($request){
        if(is_null($request)){
            return [];
        }
        $fields = $request->only(['firstname','lastname','email','avatar','username','password']);
        if(isset($fields['password'])){
            $fields['password'] = bcrypt($fields['password']);
        }
        if($request->has('gender')){
            $fields['gender_id'] = $request->get('gender');
        }
        return $fields;
    }
}