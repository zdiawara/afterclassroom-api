<?php

namespace App\Http\Resources;

use App\Http\Resources\MatiereResource;
use App\Http\Resources\ReferentielResource;
use Illuminate\Http\Resources\Json\JsonResource;


class TeacherMatiereResource extends JsonResource
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
            'matiere' => new MatiereResource($this->whenLoaded('matiere')),
            'etat' => new ReferentielResource($this->whenLoaded('etat')),
            'level' => new ReferentielResource($this->whenLoaded('level')),
            //'justificatif' => $this->justificatif,
        ];
    }
}
