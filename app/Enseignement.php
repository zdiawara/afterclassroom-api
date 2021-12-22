<?php

namespace App;

use App\Classe;
use App\Matiere;
use App\Teacher;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Enseignement extends Model
{
    protected $guarded = [];

    protected $keyType = 'string';

    public $incrementing = false;

    protected static function booted()
    {
        static::creating(function ($enseignement) {
            $enseignement->id = (string) Str::uuid();
        });
    }

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
