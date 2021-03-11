<?php

namespace App\Http\Actions\Referentiel;

use App\Referentiel;
use App\Constants\TypeReferentiel;

class FindReferentiel{

    public function __construct(){

    }

    /**
     * Recherche les referentiels par type
     */
    public function all() {        
        return Referentiel::all();
    }

    /**
     * Recherche les referentiels par type
     */
    public function byType($type) {        
        return Referentiel::where("type",$type)->get();
    }

    /**
     * 
     */
    public function byCodeAndType($code,$type) {        
        return Referentiel::where("code",$code)->where("type",$type)->firstOrFail();
    }

    /**
     * 
     */
    public function byCodeRole($code) {
        return $this->byCodeAndType($code,"role");
    }

    /**
     * 
     */
    public function byCodeControle($code) {
        return $this->byCodeAndType($code,"controle");
    }

    /**
     * 
     */
    public function byCodeEtat($code) {
        return $this->byCodeAndType($code,TypeReferentiel::ETAT);
    }

        /**
     * 
     */
    public function byCodeExercise($code) {
        return $this->byCodeAndType($code,TypeReferentiel::EXERCISE);
    }

}