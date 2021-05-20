<?php

namespace App\Http\Controllers\Api;

use App\Notion;
use App\Chapter;
use App\Http\Actions\Queries;
use App\Http\Controllers\Controller;
use App\Http\Resources\NotionResource;
use App\Http\Resources\ChapterResource;
use Symfony\Component\HttpFoundation\Request;


class NotionController extends Controller
{

    public function __construct()
    {
        //$this->middleware(['auth:api']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Queries $queries)
    {
        //
        
        $teacher = $request->get('teacher');

        $query = Notion::whereHas('teacher.user',function($q) use ($teacher){
            $q->where('username',$teacher);
        });

        $result = $queries->buildCommonQuery($query,$request);

        return NotionResource::collection($result['query']->get());

    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Chapter  $chapter
     * @return \Illuminate\Http\Response
     */
    public function show(Chapter $chapter)
    {
        return new ChapterResource($chapter);
    }

}
