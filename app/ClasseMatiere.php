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

   
    public function classe(){
        return $this->belongsTo(Classe::class);
    }

    public function matiere(){
        return $this->belongsTo(Matiere::class);
    }

    public function country(){
        return $this->belongsTo(Country::class);
    }

    public function setClasseIdAttribute($code){
        if(!is_null($code)){
            $this->attributes['classe_id'] = Classe::where("code",$code)->firstOrFail()->id;
        }
    }

    public function setMatiereIdAttribute($code){
        if(!is_null($code)){
            $this->attributes['matiere_id'] = Matiere::where("code",$code)->firstOrFail()->id;
        }
    }

    public function setCountryIdAttribute($code){
        if(!is_null($code)){
            $this->attributes['country_id'] = Country::where("code",$code)->firstOrFail()->id;
        }
    }

}
