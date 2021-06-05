<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentClasse extends Model
{
    protected $table = 'student_classe';
    protected $fillable = ['student_id', 'classe_id', 'college_year_id', 'changed'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function collegeYear()
    {
        return $this->belongsTo(CollegeYear::class);
    }
}
