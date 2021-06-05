<?php

namespace App\Http\Resources;

use App\Http\Resources\ClasseResource;
use App\Http\Resources\MatiereResource;
use App\Http\Resources\ReferentielResource;
use Illuminate\Http\Resources\Json\JsonResource;


class SubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'matiere' => new MatiereResource($this->whenLoaded('matiere')),
            'classe' => new ClasseResource($this->whenLoaded('classe')),
            'collegeYear' => new CollegeYearResource($this->whenLoaded('collegeYear')),
            'teacher' => new TeacherResource($this->whenLoaded('teacher')),
            'enseignement' => new ReferentielResource($this->whenLoaded('enseignement'))
        ];
    }
}
