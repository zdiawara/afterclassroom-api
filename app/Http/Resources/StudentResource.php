<?php

namespace App\Http\Resources;

use App\Enums\UserRole;
use App\Http\Resources\UserResource;
use App\Http\Resources\ClasseResource;
use App\Http\Resources\MatiereResource;

class StudentResource extends UserResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            "role" => UserRole::STUDENT,
            'classe' => new ClasseResource($this->whenLoaded('classe')),
            "teachers" => new TeacherResource($this->whenLoaded('teachers')),
        ];
        if ($this->student_teachers) {
            $data['teachers'] = StudentTeacherResource::collection($this->student_teachers);
        }
        if ($this->classes) {
            $data['classes'] = ClasseResource::collection($this->classes);
        }
        if ($this->matieres) {
            $data['matieres'] = MatiereResource::collection($this->matieres);
        }
        return array_merge(
            $data,
            parent::toArray($request)
        );
    }
}
