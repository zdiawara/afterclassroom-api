<?php

namespace App\Http\Actions\File;

use App\Constants\File;

class UploadFile{

    public function __construct(){
        
    }

    /**
     * 
     */
    private function uploadFile($file,$directory){
        if(!isset($file)){
            return null;
        }
        $extension = $file->getClientOriginalExtension();
        $filename  = 'file' . time() . '.' . $extension;
        $file->storeAs("public/".$directory,$filename);
        return $filename;
    }

    /**
     * 
     */
    public function cover($image){
        return $this->uploadFile($image,File::PATH_COVERS);
    }

    /**
     * 
     */
    public function image($file){
        return $this->uploadFile($file,File::PATH_IMAGES);
    }

    /**
     * 
     */
    public function justificatif($file){
        return $this->uploadFile($file,File::PATH_JUSTIFICATIFS);
    }

}