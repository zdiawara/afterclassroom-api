<?php

namespace App\Http\Actions\Exercise;

use App\Controle;
use App\Exercise;
use App\Constants\Code;


class ControleExercise {
        
    public function execute(Controle $controle, $typeContent=null){
        $query = $controle->exercises()->with(['type']);
        if($typeContent==Code::ENONCE){
            $query = $query->with('content');
        }else if($typeContent == Code::SOLUTION){
            $query = $query->with('solution.content');
        }
        return $query->get();
    }
    
    


}