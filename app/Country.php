<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    //
    protected $guarded = [];

    protected $keyType = 'string';

    public $incrementing = false;
}
