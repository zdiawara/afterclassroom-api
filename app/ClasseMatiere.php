<?php

namespace App;

use App\Country;
use App\Classe;
use App\Matiere;
use Illuminate\Database\Eloquent\Model;

class ClasseMatiere extends Model
{
    //
    protected $guarded = [];
    protected $table = 'classe_matiere';


    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
