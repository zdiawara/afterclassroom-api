<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    // Relation avec User
    public function user(){
        return $this->morphOne('App\User','userable');
    }
}
