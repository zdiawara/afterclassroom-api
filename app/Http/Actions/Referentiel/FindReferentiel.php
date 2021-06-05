<?php

namespace App\Http\Actions\Referentiel;

use App\Referentiel;

class FindReferentiel
{

    public function __construct()
    {
    }

    /**
     * Recherche les referentiels par type
     */
    public function all()
    {
        return Referentiel::all();
    }

    /**
     * Recherche les referentiels par type
     */
    public function byType($type)
    {
        return Referentiel::where("type", $type)->get();
    }
}
