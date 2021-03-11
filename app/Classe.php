<?php

namespace App;

use App\Option;
use App\Referentiel;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    //
    public function options()
    {
        return $this->hasMany(Option::class);
    }

    public function level()
    {
        return $this->belongsTo(Referentiel::class);
    }

    public function matieres()
    {
        return $this->belongsToMany(Matiere::class);
    }
}
