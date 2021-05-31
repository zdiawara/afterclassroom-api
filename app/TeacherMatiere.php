<?php

namespace App;

use App\Matiere;
use App\Teacher;
use App\Referentiel;
use App\Constants\TypeReferentiel;
use App\Exceptions\BadRequestException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeacherMatiere extends Model
{
    use SoftDeletes;

    protected $table = 'teacher_matiere';

    protected $fillable = array('teacher_id', 'matiere_id', 'justificatif', 'level_id', 'etat_id');

    protected $dates = ['deleted_at'];

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function etat()
    {
        return $this->belongsTo(Referentiel::class);
    }

    public function level()
    {
        return $this->belongsTo(Referentiel::class);
    }

    public function setMatiereIdAttribute($id)
    {
        if (!is_null($id)) {
            $this->attributes['matiere_id'] = Matiere::findOrFail($id)->id;
        }
    }

    public function setEtatIdAttribute($id)
    {
        if (!is_null($id)) {
            $etat = Referentiel::findOrFail($id);
            if ($etat->type != TypeReferentiel::ETAT) {
                throw new BadRequestException("Le référentiel " . $id . " est incorrect");
            }
            $this->attributes['etat_id'] = $id;
        }
    }

    public function setLevelIdAttribute($id)
    {
        if (!is_null($id)) {
            $etat = Referentiel::findOrFail($id);
            if ($etat->type != TypeReferentiel::LEVEL) {
                throw new BadRequestException("Le référentiel " . $id . " est incorrect");
            }
            $this->attributes['level_id'] = $id;
        }
    }
}
