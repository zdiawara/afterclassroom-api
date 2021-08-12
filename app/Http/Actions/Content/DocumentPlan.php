<?php

namespace App\Http\Actions\Content;

use DOMDocument;

class DocumentPlan
{
    /**
     * 
     */
    public function execute($data)
    {
        if (!isset($data) || $data === "") {
            return "";
        }

        $doc = new DOMDocument("1.0", "utf-8");
        $doc->loadHTML('<?xml encoding="utf-8" ?>' . $data);
        $headers = $doc->getElementsByTagName('h2');
        $content = "";
        for ($i = 0; $i < $headers->length; $i++) {
            $content .= $doc->saveHTML($headers[$i]);
        }
        return $content;
    }
}
