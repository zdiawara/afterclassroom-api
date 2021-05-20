<?php

namespace App\Http\Controllers\Api;

use App\Constants\File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Actions\File\UploadFile;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ExerciseResource;
use Illuminate\Support\Facades\Response;

class FileController extends Controller{

    public function store(request $request, UploadFile $uploadFile){
        return \response()->json([
            'path' => url('api/files/'.File::PATH_IMAGES.'/'.$uploadFile->image($request->file('file') ))
        ]);
    }

    
    public function show($root, $filename){
        return response()->file(storage_path('app/public/'.$root.'/'.$filename));
    }

}
