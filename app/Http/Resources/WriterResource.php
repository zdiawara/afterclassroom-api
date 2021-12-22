<?php

namespace App\Http\Resources;

use App\Enums\UserRole;

use App\Http\Resources\UserResource;

class WriterResource extends UserResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = parent::toArray($request);
        $user['role'] = UserRole::WRITER;
        if ($this->writer_teachers) {
            $user['writer_teachers'] = WriterResource::collection($this->teachers);
        }
        return $user;
    }
}
