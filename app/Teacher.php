<?php

namespace App;

use App\Chapter;
use App\Matiere;
use App\Student;
use App\Controle;
use App\Exercise;
use App\Referentiel;
use App\Constants\TypeReferentiel;
use App\Exceptions\BadRequestException;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{

    protected $guarded = [];

    // Relation avec User
    public function user()
    {
        return $this->morphOne('App\User', 'userable');
    }

    //
    public function matieres()
    {
        return $this->belongsToMany(Matiere::class);
    }

    public function matiereTeachers()
    {
        return $this->hasMany(MatiereTeacher::class);
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }


    public function level()
    {
        return $this->belongsTo(Referentiel::class);
    }

    public function controles()
    {
        return $this->hasMany(Controle::class);
    }

    public function exercises()
    {
        return $this->hasManyThrough(Exercise::class, Chapter::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class);
    }

    public function setLevelIdAttribute($id)
    {
        $etat = Referentiel::findOrFail($id);
        if ($etat->type != TypeReferentiel::LEVEL) {
            // Exception
            throw new BadRequestException("Referentiel incorrect", 1);
        }
        $this->attributes['level_id'] = $id;
    }
}
