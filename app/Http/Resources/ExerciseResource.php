<?php

namespace App\Http\Resources;

use App\Http\Actions\ExerciseContent;

use App\Http\Resources\ReferentielResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ExerciseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $exercise = [
            'id' => (string) $this->id,
            'type' => new ReferentielResource($this->whenLoaded('type')),
            'position' => $this->position,
            'public' => (string) $this->is_public,
            'notions' => $this->notions,
            'prerequis' => $this->prerequis,
            'updated' => $this->updated_at,
            'created' => $this->created_at,
        ];

        return array_merge($exercise, (new ExerciseContent)->build($this));
    }
}
