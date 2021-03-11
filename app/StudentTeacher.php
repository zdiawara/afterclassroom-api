<?php

namespace App;

use App\Classe;
use App\Matiere;
use App\Teacher;
use App\CollegeYear;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentTeacher extends Model
{
    //
    use SoftDeletes;
    
    protected $guarded = [];

    protected $table = 'student_teacher';


    public function matiere(){
        return $this->belongsTo(Matiere::class);
    }

    public function classe(){
        return $this->belongsTo(Classe::class);
    }
    
    public function teacher(){
        return $this->belongsTo(Teacher::class);
    }

    public function collegeYear(){
        return $this->belongsTo(CollegeYear::class);
    }
}
