<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClasseMatiereResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'classe' => new ClasseResource($this->whenLoaded('classe')),
            'matiere' => new ClasseResource($this->whenLoaded('matiere')),
            'teacher' => new TeacherResource($this->whenLoaded('teacher')),
        ];
    }
}
