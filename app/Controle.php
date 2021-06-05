<?php

namespace App;

use App\Referentiel;
use App\Constants\TypeReferentiel;
use App\Exceptions\BadRequestException;

class Controle extends Enseignement
{

    protected $keyType = 'string';

    public $incrementing = false;

    public function type()
    {
        return $this->belongsTo(Referentiel::class);
    }

    public function trimestre()
    {
        return $this->belongsTo(Referentiel::class);
    }

    public function setTypeIdAttribute($id)
    {
        if (!is_null($id)) {
            $ref = Referentiel::findOrFail($id);
            if ($ref->type != TypeReferentiel::CONTROLE) {
                throw new BadRequestException("Le référentiel " . $id . " est incorrect");
            }
            $this->attributes['type_id'] = $ref->id;
        }
    }

    public function setTrimestreIdAttribute($id)
    {
        if (!is_null($id)) {
            $ref = Referentiel::findOrFail($id);
            if ($ref->type != TypeReferentiel::TRIMESTRE) {
                throw new BadRequestException("Le référentiel " . $id . " est incorrect");
            }
            $this->attributes['trimestre_id'] = $ref->id;
        }
    }
}
