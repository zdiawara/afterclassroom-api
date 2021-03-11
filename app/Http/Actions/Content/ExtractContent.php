<?php

namespace App\Http\Actions\Content;

use DOMDocument;

class ExtractContent{
    /**
     * 
     */
    public function execute($data){
        if(!isset($data) || $data===""){
            return "";
        }

        $bloks = collect(['div','p','ul','ol']);
        $doc = new DOMDocument("1.0", "utf-8");
        $doc->loadHTML('<?xml encoding="utf-8" ?>'.$data);
        $childNodes = $doc->getElementsByTagName('body')[0]->childNodes;
        $content = "";
        $totalBlock = 0;
        
        for ($i=0; $i < $childNodes->length; $i++) {
            if($totalBlock>=1){ break; }
            if($bloks->contains($childNodes[$i]->nodeName)){
                $totalBlock++;
            }
            $content .= $doc->saveHTML($childNodes[$i]);
        }
        return $content.' <a href="/">Voir la suite</a>  ';
    }
}