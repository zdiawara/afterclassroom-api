<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Referentiel extends Model
{
    protected $fillable = ['name', 'type', 'position', 'id'];
    protected $keyType = 'string';
    public $incrementing = false;
}
