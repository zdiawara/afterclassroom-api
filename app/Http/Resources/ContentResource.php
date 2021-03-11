<?php

namespace App\Http\Resources;

use App\Http\Actions\Checker\UserChecker;
use App\Http\Actions\Content\ExtractContent;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentResource extends JsonResource{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = auth()->userOrFail();
        
        return [
            'id' => (string) $this->id,
            'active' => (string) $this->active,
            'data' => (new UserChecker)->canReadContent($user,$this) 
                ? $this->data 
                : (new ExtractContent)->execute($this->data),
            'contentable' => (string) $this->contentable_id,
            "type" => $this->contentable_type,
            'updated' => date('d/m/Y', strtotime($this->updated_at))
        ];
    }
}
