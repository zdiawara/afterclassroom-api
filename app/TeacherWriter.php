<?php

namespace App;

use App\Writer;
use App\Teacher;
use Illuminate\Database\Eloquent\Model;

class TeacherWriter extends Model
{
    protected $table = 'teacher_writer';

    protected $fillable = array('teacher_id', 'writer_id');

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function writer()
    {
        return $this->belongsTo(Writer::class);
    }
}
