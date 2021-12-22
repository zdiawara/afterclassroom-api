<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $guarded = [];

    protected $keyType = 'string';

    public $incrementing = false;

    protected static function booted()
    {
        static::creating(function ($exercise) {
            $exercise->id = (string) Str::uuid();
        });
    }

    //
    public function notion()
    {
        return $this->belongsTo(Notion::class);
    }
}
