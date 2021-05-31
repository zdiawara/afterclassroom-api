<?php

namespace App\Http\Resources;

use App\Constants\File;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $model = is_null($this->user) ? $this : $this->user;

        //$model->load('gender');



        $fields = [
            'firstname' => $model->firstname,
            'lastname' => $model->lastname,
            'email' => $model->email,
            'username' => (string)$model->username,
            'avatar' => url('api/files/' . File::PATH_IMAGES . '/' . $model->avatar),
        ];


        if (!is_null($this->user)) {
            $fields['gender'] = new ReferentielResource($this->user->gender);
        }

        return $fields;
    }
}
