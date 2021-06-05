<?php

namespace App;

use App\Classe;
use App\Teacher;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $guarded = [];

    protected $keyType = 'string';

    public $incrementing = false;

    // Relation avec User
    public function user()
    {
        return $this->morphOne('App\User', 'userable');
    }

    public function classes()
    {
        return $this->belongsToMany(Classe::class, 'student_classe')
            ->withPivot('classe_id', 'college_year_id')
            ->withTimestamps();
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class)->withPivot('matiere_id')->withTimestamps();
    }
}
