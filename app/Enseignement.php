<?php

namespace App;

use App\Classe;
use App\Matiere;
use App\Teacher;
use Illuminate\Database\Eloquent\Model;

class Enseignement extends Model
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

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function setMatiereIdAttribute($code)
    {
        if (!is_null($code)) {
            $this->attributes['matiere_id'] = Matiere::where("code", $code)->firstOrFail()->id;
        }
    }

    public function setClasseIdAttribute($code)
    {
        if (!is_null($code)) {
            $this->attributes['classe_id'] = Classe::where("code", $code)->firstOrFail()->id;
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
