<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeacherWriterResource extends JsonResource
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
            'teacher' => new TeacherResource($this->whenLoaded('teacher')),
            'writer' => new WriterResource($this->whenLoaded('writer')),
        ];
    }
}
