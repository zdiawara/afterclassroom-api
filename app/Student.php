<?php

namespace App;

use App\Classe;
use App\Option;
use App\Teacher;
use App\Exceptions\PrivilegeException;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $guarded = [];
    
    // Relation avec User
    public function user(){
        return $this->morphOne('App\User','userable');
    }

    public function classe(){
        return $this->belongsTo(Classe::class);
    }

    public function option(){
        return $this->belongsTo(Option::class);
    }

    public function teachers(){
        return $this->belongsToMany(Teacher::class)->withPivot('matiere_id');
    }

    public function setClasseIdAttribute($code){
        if(!is_null($code)){
            $this->attributes['classe_id'] = Classe::where("code",$code)->firstOrFail()->id;
            $this->attributes['option_id'] = null;
        }
    }

    public function setOptionIdAttribute($code){
        $this->attributes['option_id'] = null;
        if(is_null($id)){
            return;
        }
        $option = Option::where('classe_id',$this->classe_id)->where('code',$code)->first();
        
        if(!isset($option)){
            throw new PrivilegeException("Impossible d'associer cette option et la classe !");
        }
        $this->attributes['option_id'] = $option->id;
    }
}
