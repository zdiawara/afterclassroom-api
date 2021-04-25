<?php

namespace App\Http\Resources;

use App\Http\Resources\EnseignementResource;

class ChapterResource extends EnseignementResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge(
            parent::toArray($request),
            [
                'title' => $this->title,
                'resume' => $this->resume,
                'position' => $this->position,
                "public" => (string) $this->is_public,
                "toc" => (string) $this->toc,
                'content' => [
                    "data" => $this->content,
                    'active' => (string) $this->is_active,
                ],
                'exercises' => ExerciseResource::collection($this->whenLoaded('exercises'))
            ]
        );
    }
}
