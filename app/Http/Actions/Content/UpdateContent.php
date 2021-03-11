<?php

namespace App\Actions\Content;

use App\Content;

class UpdateContent{
    /**
     * 
     */
    public function execute($newContent) : Content{

        $content = Content::findOrFail(intval($newContent['id']));
        
        $content->data = $newContent['data'];

        $content->save();

        return $content;
    }
}