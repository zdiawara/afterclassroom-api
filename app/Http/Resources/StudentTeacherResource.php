<?php

namespace App\Http\Resources;

use App\Http\Resources\ClasseResource;
use App\Http\Resources\MatiereResource;
use App\Http\Resources\ReferentielResource;
use Illuminate\Http\Resources\Json\JsonResource;


class StudentTeacherResource extends JsonResource{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request){
        return [
            'matiere' => new MatiereResource($this->whenLoaded('matiere')),
            'classe' => new ClasseResource($this->whenLoaded('classe')),
            'collegeYear' => new CollegeYearResource($this->whenLoaded('collegeYear')),
            'teacher' => new TeacherResource($this->whenLoaded('teacher'))
        ];
    }
}
