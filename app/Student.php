<?php

namespace App;

use App\Classe;
use App\Teacher;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $guarded = [];

    // Relation avec User
    public function user()
    {
        return $this->morphOne('App\User', 'userable');
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class)->withPivot('matiere_id');
    }

    public function setClasseIdAttribute($code)
    {
        if (!is_null($code)) {
            $this->attributes['classe_id'] = Classe::where("code", $code)->firstOrFail()->id;
        }
    }
}
