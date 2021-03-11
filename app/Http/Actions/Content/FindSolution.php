<?php

namespace App\Actions\Content;

use App\Content;
use App\Exercise;

class FindSolution{
    /**
     * 
     */
    public function execute(int $enseignementID) : Content{
        $exercise = Exercise::findOrFail($enseignementID);
        $exercise->load('solution.content');
        return $exercise->solution->content;
    }
}