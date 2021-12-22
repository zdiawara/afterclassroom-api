<?php

namespace App;

use App\Referentiel;

class Controle extends Enseignement
{

    protected $keyType = 'string';

    public $incrementing = false;

    public function type()
    {
        return $this->belongsTo(Referentiel::class);
    }

    public function trimestre()
    {
        return $this->belongsTo(Referentiel::class);
    }

    public function session()
    {
        return $this->belongsTo(Referentiel::class);
    }
}
