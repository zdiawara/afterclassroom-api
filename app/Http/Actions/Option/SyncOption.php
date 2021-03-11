<?php

namespace App\Http\Actions\Option;

class SyncOption{

    public function execute($model,$fields){
        
        if(!isset($fields['options'])){
            return;
        }
        
        $model->options()->sync(collect($fields['options'])->map(function($option){
            return $option;
        }));
    }

}