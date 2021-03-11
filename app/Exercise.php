<?php

namespace App;

use App\Notion;
use App\Solution;
use App\Referentiel;
use App\Constants\TypeReferentiel;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    protected $guarded = [];
    
    //
    public function type(){
        return $this->belongsTo(Referentiel::class);
    }

    //
    public function chapter(){
        return $this->belongsTo(Chapter::class);
    }

    public function setTypeIdAttribute($code){
        if(!is_null($code)){
            $this->attributes['type_id'] = Referentiel::where("code",$code)->where("type",TypeReferentiel::EXERCISE)->firstOrFail()->id;
        }
    }
}
