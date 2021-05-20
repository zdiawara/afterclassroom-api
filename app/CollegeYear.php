<?php

namespace App;

use App\Referentiel;
use Illuminate\Database\Eloquent\Model;

class CollegeYear extends Model
{
    //
    
    protected $guarded = [];

    public function etat(){
        return $this->belongsTo(Referentiel::class);
    }
}
