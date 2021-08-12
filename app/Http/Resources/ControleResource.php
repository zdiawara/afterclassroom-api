<?php

namespace App\Http\Resources;

use App\Http\Actions\ExerciseContent;
use App\Http\Resources\ReferentielResource;
use App\Http\Resources\EnseignementResource;

class ControleResource extends EnseignementResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $controle = [
            'id' =>  $this->id,
            'year' => $this->year,
            'type' => new ReferentielResource($this->whenLoaded('type')),
            'trimestre' => new ReferentielResource($this->whenLoaded('trimestre')),
            'position' => $this->position,
            'accessible' => (string) $this->is_public,
            'updated' => $this->updated_at,
            'created' => $this->created_at,
        ];

        return array_merge($controle, (new ExerciseContent)->build($this));
    }
}
