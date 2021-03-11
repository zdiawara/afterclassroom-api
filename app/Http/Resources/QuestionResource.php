<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
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
            'title' => $this->title,
            'active' => (string) $this->active,
            'content' => $this->content,
            'chapter' => new ChapterResource($this->whenLoaded('chapter')),
            'updated' => date('d/m/Y', strtotime($this->updated_at)),
        ];
    }
}
