<?php

namespace App\Http\Resources;

class NotionResource extends EnseignementResource
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
                'active' => (string) $this->is_active,
                'position' => $this->position
            ]
        );
    }
}
