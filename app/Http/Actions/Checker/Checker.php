<?php

namespace App\Http\Actions\Checker;

use App\Exceptions\NotFoundException;
use App\Exceptions\PrivilegeException;
use App\Exceptions\BadRequestException;


class Checker{

    protected function contains($models,$model){
        return collect($models)->contains(function ($value) use ($model) {
            return $model instanceof $value;
        });
    }

    protected function createPrivilegeException($message = "Vous n'avez pas les droits pour créer cette ressource."){
        throw new PrivilegeException($message);
    }

    protected function updatePrivilegeException($message = "Vous n'avez pas les droits pour modifier cette ressource."){
        throw new PrivilegeException($message);
    }

    protected function deletePrivilegeException($message = "Vous n'avez pas les droits pour supprimer cette ressource."){
        throw new PrivilegeException($message);
    }

    protected function notFoundException($message = "Ressource introuvable !"){
        throw new NotFoundException($message);
    }

    protected function badRequestException($message = "Requête incorrecte !"){
        throw new BadRequestException($message);
    }

}