<?php

namespace App\Rules;

use App\Referentiel;
use Illuminate\Contracts\Validation\Rule;

class ReferentielRule implements Rule
{
    
    public function check($id,$type)
    {
        $referentiel = Referentiel::find($id);
        return isset($referentiel) && $referentiel->type == $type;
    }


}
