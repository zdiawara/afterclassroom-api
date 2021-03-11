<?php

namespace App\Http\Resources;

use App\Http\Resources\ClasseResource;
use App\Http\Resources\MatiereResource;
use App\Http\Actions\Checker\UserChecker;
use App\Http\Resources\SpecialiteResource;
use Illuminate\Http\Resources\Json\JsonResource;

class EnseignementResource extends JsonResource
{

    public function canReadContent()
    {
        return (new UserChecker)->canReadContent(auth()->userOrFail(), $this);
    }

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
            'matiere' => new MatiereResource($this->whenLoaded('matiere')),
            'specialite' => new SpecialiteResource($this->whenLoaded('specialite')),
            'classe' => new ClasseResource($this->whenLoaded('classe')),
            'teacher' => new UserResource($this->whenLoaded('teacher')),
            'options' => OptionResource::collection($this->whenLoaded('options')),
            'updated' => date('d/m/Y', strtotime($this->updated_at))
        ];
    }
}
