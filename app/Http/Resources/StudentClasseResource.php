<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class StudentClasseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'collegeYear' => new CollegeYearResource($this->whenLoaded('collegeYear')),
            'classe' => new ClasseResource($this->whenLoaded('classe')),
            'changed' => $this->changed
        ];
    }
}
