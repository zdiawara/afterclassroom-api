<?php

namespace App;

use App\Matiere;
use Illuminate\Database\Eloquent\Model;

class Specialite extends Model
{
    //
    protected $guarded = [];

    protected $keyType = 'string';

    public $incrementing = false;

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    public function setMatiereIdAttribute($id)
    {
        if (!is_null($id)) {
            $this->attributes['matiere_id'] = Matiere::findOrFail($id)->id;
        }
    }
}
