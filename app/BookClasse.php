<?php

namespace App;

use App\Book;
use App\Classe;
use Illuminate\Database\Eloquent\Model;

class BookClasse extends Model
{
    //
    protected $guarded = [];
    protected $table = 'book_classe';

    public function options(){
        return $this->morphToMany('App\Option', 'enseignementable');
    }

    
    public function classe(){
        return $this->belongsTo(Classe::class);
    }

    public function book(){
        return $this->belongsTo(Book::class);
    }

    public function setBookIdAttribute($id){
        if(!is_null($id)){
            $this->attributes['book_id'] = Book::findOrFail($id)->id;
        }
    }

    public function setClasseIdAttribute($id){
        if(!is_null($id)){
            $this->attributes['classe_id'] = classe::findOrFail($id)->id;
        }
    }

}
