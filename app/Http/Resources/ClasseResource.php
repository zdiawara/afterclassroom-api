<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClasseResource extends JsonResource
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
            'id' => (string) $this->id,
            'name' => $this->name,
            'abreviation' => $this->abreviation,
            'position' => $this->position,
            'hasFaq' => (string) $this->has_faq,
            'isExamClass' => (string) $this->is_exam_class,
            'level' => new ReferentielResource($this->whenLoaded('level')),
            'matieres' => MatiereResource::collection($this->whenLoaded('matieres')),
        ];
    }
}
