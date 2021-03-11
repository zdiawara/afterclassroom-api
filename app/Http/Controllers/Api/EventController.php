<?php

namespace App\Http\Controllers\Api;

use App\Chapter;
use App\Events\ChapterCreatedEvent;
use App\Http\Controllers\Controller;

class EventController extends Controller
{
    public function readEvents()
    {
        $event = new ChapterCreatedEvent(Chapter::find(1));

        broadcast($event);

        return "ok";
    }
}
