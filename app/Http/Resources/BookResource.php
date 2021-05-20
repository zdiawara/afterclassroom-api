<?php

namespace App\Http\Resources;

use App\Constants\File;
use App\Http\Resources\ClasseResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\EnseignementResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends EnseignementResource
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
    
        
        return array_merge(
            \collect($enseignement)->except(['classe'])->all(),
            [
                'title' => $this->title,
                'resume' => $this->resume,
                'active' => (string) $this->active,
                'price' => $this->price,
                'cover' => url('api/files/'.File::PATH_IMAGES.'/'.$this->cover),
                'classes' => ClasseResource::collection($this->whenLoaded('classes')),
            ]
        );
    }
}
