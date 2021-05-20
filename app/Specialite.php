<?php

namespace App;

use App\Matiere;
use Illuminate\Database\Eloquent\Model;

class Specialite extends Model
{
    //
    protected $guarded = [];

    public function matiere(){
        return $this->belongsTo(Matiere::class);
    }
}
