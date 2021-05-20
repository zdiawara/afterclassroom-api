<?php

namespace App\Http\Actions\User;

use App\Identify;

class ManageIdentify{
    
    private const MAX = 999;

    private const KEY = "ACDFPMSTUYZ";

    public function __construct()
    {
    }

    public function buildIdentify(){

        $identify = Identify::firstOrFail();
        
        if($identify->current > self::MAX || $identify->current < 0){
            $identify->current = 0;
        }

        $numero = $identify->tranche * 1000 + $identify->current;

        $modulo = $numero % 11;

        if($identify->current === self::MAX){
            $identify->tranche += 1;
            $identify->current = 0;
        }else{
            $identify->current += 1 ;
        }
        
        $identify->save();

        return $numero.self::KEY[$modulo];

    }

}
