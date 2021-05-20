<?php

namespace App;

use App\Referentiel;
use App\Constants\TypeReferentiel;
use App\Exceptions\BadRequestException;

class Controle extends Enseignement
{

    public function type()
    {
        return $this->belongsTo(Referentiel::class);
    }

    public function subject()
    {
        return $this->belongsTo(Referentiel::class);
    }

    public function trimestre()
    {
        return $this->belongsTo(Referentiel::class);
    }

    public function setTypeIdAttribute($code)
    {
        if (!is_null($code)) {
            $ref = Referentiel::where('code', $code)->where('type', TypeReferentiel::CONTROLE)->first();
            if (\is_null($ref)) {
                throw new BadRequestException("Referentiel incorrect " . $code);
            }
            $this->attributes['type_id'] = $ref->id;
        }
    }

    public function setTrimestreIdAttribute($code)
    {
        if (!is_null($code)) {
            $ref = Referentiel::where('code', $code)->where('type', TypeReferentiel::TRIMESTRE)->first();
            if (is_null($ref)) {
                throw new BadRequestException("Le référentiel " . $code . " est incorrect");
            }
            $this->attributes['trimestre_id'] = $ref->id;
        }
    }

    public function setSubjectIdAttribute($code)
    {
        if (!is_null($code)) {
            $ref = Referentiel::where('code', $code)->where('type', TypeReferentiel::EXAMEN)->first();
            if (is_null($ref)) {
                throw new BadRequestException("Le référentiel " . $code . " est incorrect");
            }
            $this->attributes['subject_id'] = $ref->id;
        }
    }
}
