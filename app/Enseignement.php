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

    // public function setMatiereIdAttribute($id)
    // {
    //     if (!is_null($id)) {
    //         $this->attributes['matiere_id'] = Matiere::findOrFail($id)->id;
    //     }
    // }

    // public function setClasseIdAttribute($id)
    // {
    //     if (!is_null($id)) {
    //         $this->attributes['classe_id'] = Classe::findOrFail($id)->id;
    //     }
    // }

    // public function setTeacherIdAttribute($id)
    // {
    //     if (!is_null($id)) {
    //         $this->attributes['teacher_id'] = Teacher::findOrFail($id)->id;
    //     }
    // }
}
