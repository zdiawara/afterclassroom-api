<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Writer extends Model
{

    protected $guarded = [];

    protected $keyType = 'string';

    public $incrementing = false;

    // Relation avec User
    public function user()
    {
        return $this->morphOne('App\User', 'userable');
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'teacher_writer')->withTimestamps();;
    }
}
