<?php

namespace App\Http\Resources;

use App\Enums\UserRole;

use App\Http\Resources\UserResource;
use App\Http\Resources\TeacherMatiereResource;

class TeacherResource extends UserResource
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

        $user['role'] = UserRole::TEACHER;
        if (isset($this->teacher_writers)) {
            $user['writers'] = WriterResource::collection($this->writers);
        }
        if ($this->teacher_matieres) {
            $user['matieres'] = TeacherMatiereResource::collection($this->teacher_matieres);
        }
        if ($this->classes) {
            $user['classes'] = ClasseResource::collection($this->classes);
        }
        if (isset($this->exercises_count)) {
            $user['exercises'] = $this->exercises_count;
        }
        if (isset($this->chapters_count)) {
            $user['chapters'] = $this->chapters_count;
        }
        if (isset($this->controles_count)) {
            $user['controles'] = $this->controles_count;
        }
        if (isset($this->students_count)) {
            $user['students'] = $this->students_count;
        }
        if (isset($this->collegeYear)) {
            $user['collegeYear'] = new CollegeYearResource($this->collegeYear);
        }
        return $user;
    }
}
