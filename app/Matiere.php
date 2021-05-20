<?php

namespace App;

use App\Specialite;
use Illuminate\Database\Eloquent\Model;

class Matiere extends Model
{
    //
    public function specialites(){
        return $this->hasMany(Specialite::class);
    }
}
