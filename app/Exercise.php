<?php

namespace App;

use App\Referentiel;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
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
    public function type()
    {
        return $this->belongsTo(Referentiel::class);
    }

    //
    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
}
