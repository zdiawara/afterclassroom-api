<?php

namespace App\Http\Actions\User;

use App\User;
use App\Http\Actions\User\UserField;
use App\Http\Actions\File\UploadFile;
use App\Http\Actions\User\ManageIdentify;


class ManageUser
{

    private $uploadFile;

    private $manageIdentify;

    public function __construct(UploadFile $uploadFile, ManageIdentify $manageIdentify)
    {
        $this->uploadFile = $uploadFile;
        $this->manageIdentify = $manageIdentify;
    }

    private function buildUserFields($request)
    {
        if (is_null($request)) {
            return [];
        }
        $fields = $request->only(['firstname', 'lastname', 'email', 'avatar', 'username', 'password']);
        if (isset($fields['password'])) {
            $fields['password'] = bcrypt($fields['password']);
        }
        if ($request->has('gender')) {
            $fields['gender_id'] = $request->get('gender');
        }
        return $fields;
    }

    public function create($request, string $username)
    {
        $user = new User($this->buildUserFields($request), ['username' => $username]);

        $user->username = $this->manageIdentify->buildIdentify();

        $user->avatar = $request->has('avatar')
            ?   $this->uploadFile->image($request->file('avatar'))
            :   'avatar.png';
        return $user;
    }

    public function updateAvatar($user, $request)
    {
        if ($request->file('avatar')) {
            $avatar = $this->uploadFile->image($request->file('avatar'));
            if (isset($avatar)) {
                $user->update(['avatar' => $avatar]);
            }
        }
    }
}
