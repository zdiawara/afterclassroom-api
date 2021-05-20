<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $guarded = [];

    //
    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
}
