<?php

namespace App;

use App\Referentiel;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{

    protected $keyType = 'string';

    public $incrementing = false;

    public function level()
    {
        return $this->belongsTo(Referentiel::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function matieres()
    {
        return $this->belongsToMany(Matiere::class)
            ->withTimestamps();
    }
}
