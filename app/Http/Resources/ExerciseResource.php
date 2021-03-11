<?php

namespace App\Http\Resources;

use App\Http\Actions\ExerciseContent;

use App\Http\Actions\Checker\UserChecker;
use App\Http\Resources\ReferentielResource;
use App\Http\Actions\Content\ExtractContent;
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
            'notions' => $this->notions,
            'prerequis' => $this->prerequis,
            'updated' => date('d/m/Y', strtotime($this->updated_at)),
        ];

        return array_merge($exercise, (new ExerciseContent)->build($this, $this->chapter));
    }
}
