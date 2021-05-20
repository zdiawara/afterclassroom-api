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
            'year' => $this->year,
            'type' => new ReferentielResource($this->whenLoaded('type')),
            'trimestre' => new ReferentielResource($this->whenLoaded('trimestre')),
            'subject' => new ReferentielResource($this->whenLoaded('subject')),            
        ];
        return array_merge(parent::toArray($request), $controle,(new ExerciseContent)->build($this));
    }
}
