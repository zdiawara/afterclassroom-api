<?php

namespace App;

use App\Classe;
use App\Matiere;
use App\Teacher;
use App\CollegeYear;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    //
    use SoftDeletes;

    protected $guarded = [];

    protected $keyType = 'string';

    public $incrementing = false;

    protected static function booted()
    {
        static::creating(function ($subscription) {
            $subscription->id = (string) Str::uuid();
        });
    }

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function enseignement()
    {
        return $this->belongsTo(Referentiel::class);
    }

    public function collegeYear()
    {
        return $this->belongsTo(CollegeYear::class);
    }
}
