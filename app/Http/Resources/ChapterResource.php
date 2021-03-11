<?php

namespace App\Http\Resources;

use App\Http\Actions\Content\ExtractContent;
use App\Http\Resources\EnseignementResource;
use Illuminate\Http\Resources\Json\JsonResource;

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
        $enseignement = parent::toArray($request);

        return array_merge($enseignement, [
            'title' => $this->title,
            'resume' => $this->resume,
            'active' => (string) $this->active,
            'position' => $this->position,
            'content' => $this->canReadContent()
                ? $this->content
                : (new ExtractContent)->execute($this->content),
            'exercises' => ExerciseResource::collection($this->whenLoaded('exercises'))
        ]);
    }
}
