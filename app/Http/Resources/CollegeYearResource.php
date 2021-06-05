<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class CollegeYearResource extends JsonResource
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
            'year' => $this->id,
            'name' => $this->name,
            'started_at' => $this->started_at,
            'finished_at' => $this->finished_at
        ];
    }
}
