<?php

namespace App\Http\Actions;

use App\Book;
use App\Classe;
use App\BookClasse;

class Sync{


    public function syncClasses($model, $fields){
        if(!isset($fields['classes'])){
            return;
        }
        $model->classes()->sync($fields['classes']);
    }

    public function syncCategories($model, $fields){
        if(!isset($fields['categories'])){
            return;
        }
        $model->categories()->sync($fields['categories']);
    }


}