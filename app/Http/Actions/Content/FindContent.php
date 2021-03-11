<?php

namespace App\Actions\Content;

use App\Content;

class FindContent{
    /**
     * 
     */
    public function execute(int $id, $class=null) : Content{
        if($class==null){
            return Content::findOrFail(intval($id));
        }
        $enseignement = $class::findOrFail($id);
        $enseignement->load(['content']);
        return $enseignement->content;
    }
}