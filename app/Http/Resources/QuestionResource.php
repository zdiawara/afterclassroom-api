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
            'active' => (string) $this->is_active,
            'content' => $this->content,
            'chapter' => new NotionResource($this->whenLoaded('chapter')),
            'updated' => $this->updated_at,
            'created' => $this->created_at
        ];
    }
}
