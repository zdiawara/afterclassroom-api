<?php

namespace App\Http\Resources;

use App\Enums\UserRole;
use App\Http\Resources\UserResource;

class StudentResource extends UserResource
{
    public function toArray($request)
    {
        $data = [
            "role" => UserRole::STUDENT,
        ];

        if ($this->studentClasse) {
            $data['level'] = new StudentClasseResource($this->studentClasse);
        }
        if ($this->collegeYear) {
            $data['collegeYear'] = new CollegeYearResource($this->collegeYear);
        }
        if ($this->subscriptions) {
            $data['subscriptions'] = SubscriptionResource::collection($this->subscriptions);
        }
        return array_merge(
            $data,
            parent::toArray($request)
        );
    }
}
