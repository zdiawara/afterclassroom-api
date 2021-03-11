<?php

namespace App;

use Exception;
use App\Matiere;
use App\Teacher;
use App\Referentiel;
use App\Constants\CodeReferentiel;
use App\Constants\TypeReferentiel;
use App\Exceptions\BadRequestException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MatiereTeacher extends Model
{
    use SoftDeletes;

    protected $table = 'matiere_teacher';

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

    public function setMatiereIdAttribute($code)
    {
        if (!is_null($code)) {
            $this->attributes['matiere_id'] = Matiere::where('code', $code)->firstOrFail()->id;
        }
    }

    public function setEtatIdAttribute($id)
    {
        if (!is_null($id)) {
            $etat = Referentiel::findOrFail($id);
            if ($etat->type != TypeReferentiel::ETAT) {
                throw new Exception("Referentiel incorrect", 1);
            }
            $this->attributes['etat_id'] = $etat->id;
        }
    }

    public function setLevelIdAttribute($code)
    {
        if (!is_null($code)) {
            $level = Referentiel::where('code', $code)
                ->where('type', TypeReferentiel::LEVEL)
                ->firstOrFail();
            if (!isset($level)) {
                throw new BadRequestException("Referentiel incorrect " . $code, 1);
            }
            $this->attributes['level_id'] = $level->id;
        }
    }
}
