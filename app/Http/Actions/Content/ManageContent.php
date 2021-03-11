<?php

namespace App\Http\Actions\Content;

use App\Content;


class ManageContent{
    /**
     * 
     */
    public function create($enseignement) : Content{
        $content = new Content([
            'active' => $enseignement->active || 0,
            'data' => ''
        ]);
        $enseignement->content()->save($content);
        return $content;
    }
}