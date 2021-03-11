<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MatiereResource extends JsonResource
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
            'code' => $this->code,
            'abreviation' => $this->abreviation,
            'position' => $this->position,
            'specialites' => SpecialiteResource::collection($this->whenLoaded('specialites')),
        ];
    }
}
