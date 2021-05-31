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

    // public function setStudentIdAttribute($id)
    // {
    //     if (!is_null($id)) {
    //         $this->attributes['student_id'] = Student::findOrFail($id)->id;
    //     }
    // }

    // public function setClasseIdAttribute($id)
    // {
    //     if (!is_null($id)) {
    //         $this->attributes['classe_id'] = Classe::findOrFail($id)->id;
    //     }
    // }

    // public function setCollegeYearIdAttribute($id)
    // {
    //     if (!is_null($id)) {
    //         $this->attributes['college_year_id'] = CollegeYear::findOrFail($id)->id;
    //     }
    // }
}
