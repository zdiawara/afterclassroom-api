<?php

namespace App;

use App\Specialite;
use App\Exceptions\PrivilegeException;

class Notion extends Enseignement
{
    protected $guarded = [];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function specialite()
    {
        return $this->belongsTo(Specialite::class);
    }

    public function setSpecialiteIdAttribute($id)
    {
        if (!is_null($id)) {
            $specialite = Specialite::find($id);
            if (isset($specialite) && $specialite->matiere_id == $this->matiere_id) {
                $this->attributes['specialite_id'] = $specialite->id;
            } else {
                throw new PrivilegeException("Impossible d'associer cette spécialité et la matière !");
            }
        } else {
            $this->attributes['specialite_id'] = null;
        }
    }
}
