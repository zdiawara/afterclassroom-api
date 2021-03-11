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
        $classe = [
            'id' => (string) $this->id,
            'name' => $this->name,
            'abreviation' => $this->abreviation,
            'position' => $this->position,
            'code' => $this->code,
            'matieres' => MatiereResource::collection($this->whenLoaded('matieres')),
            'level' => new ReferentielResource($this->whenLoaded('level')),
        ];

        if (isset($this->exercises_count)) {
            $classe['exercises_count'] = $this->exercises_count;
        }

        if (isset($this->controles_count)) {
            $classe['controles_count'] = $this->controles_count;
        }

        return $classe;
    }
}
