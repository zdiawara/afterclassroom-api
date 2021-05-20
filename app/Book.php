<?php

namespace App;

use App\Classe;
use App\Matiere;
use App\Teacher;
use App\Specialite;
use App\Enseignement;
use App\Exceptions\PrivilegeException;

class Book extends Enseignement
{
    //
    protected $guarded = [];


    public function content()
    {
        return $this->morphOne('App\Content', 'contentable');
    }

    public function classes()
    {
        return $this->belongsToMany(Classe::class);
    }


    public function setMatiereIdAttribute($code)
    {
        if (!is_null($code)) {
            $this->attributes['matiere_id'] = Matiere::where("code", $code)->firstOrFail()->id;
        }
    }

    public function setSpecialiteIdAttribute($id)
    {
        if (!is_null($id)) {
            $specialite = Specialite::where('matiere_id', $this->matiere_id)->where('id', $id)->first();

            if (isset($specialite)) {
                $this->attributes['specialite_id'] = $specialite->id;
            } else {
                throw new PrivilegeException("Impossible d'associer cette spécialité et la matière !");
            }
        } else {
            $this->attributes['specialite_id'] = null;
        }
    }

    public function setTeacherIdAttribute($username)
    {
        if (!is_null($username)) {
            $this->attributes['teacher_id'] = Teacher::whereHas('user', function ($q) use ($username) {
                $q->where('username', $username);
            })->firstOrFail()->id;
        }
    }
}
