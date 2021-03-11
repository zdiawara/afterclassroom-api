<?php

namespace App;

use App\Classe;
use App\Matiere;
use App\Teacher;
use App\Specialite;
use App\Exceptions\PrivilegeException;

class Chapter extends Enseignement
{
    protected $guarded = [];

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function specialite()
    {
        return $this->belongsTo(Specialite::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function exercises()
    {
        return $this->hasMany(Exercise::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function setSpecialiteIdAttribute($code)
    {
        if (!is_null($code)) {
            $specialite = Specialite::where('code', $code)->first();
            if (isset($specialite)) {
                $this->attributes['specialite_id'] = $specialite->id;
            } else {
                throw new PrivilegeException("Impossible d'associer cette spécialité et la matière !");
            }
        } else {
            $this->attributes['specialite_id'] = null;
        }
    }
}
