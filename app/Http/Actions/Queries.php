<?php

namespace App\Http\Actions;

class Queries
{

    private $query;

    private function __construct($query)
    {
        $this->query = $query;
    }

    public static function of($query)
    {
        return new Queries($query);
    }

    public function addMatiere(array $fields)
    {
        if (isset($fields['matiere'])) {
            return $this->whereHas('matiere', $fields['matiere']);
        }
        return $this;
    }

    public function addSpecialite(array $fields)
    {
        if (isset($fields['specialite'])) {
            return $this->whereHas('specialite', $fields['specialite']);
        }
        return $this;
    }

    public function addTypeControle($type)
    {
        if (isset($type)) {
            return $this->whereHas('type', $type);
        }
        return $this;
    }

    public function addClasse(array $fields)
    {
        if (isset($fields['classe'])) {
            return $this->whereHas('classe', $fields['classe']);
        }
        return $this;
    }

    public function orderByPosition()
    {
        $this->query = $this->query->orderBy('position', 'asc');
        return $this;
    }

    private function whereHas($relation, $code)
    {
        $this->query = $this->query->whereHas($relation, function ($q) use ($code) {
            $q->where('code', $code);
        });
        return $this;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function get()
    {
        return $this->query->get();
    }

    public function addActive($canReadInactive)
    {
        if (!$canReadInactive) {
            $this->query = $this->query->where('is_active', 1);
        }
        return $this;
    }

    public function addEnonceActive(bool $canReadInactive)
    {
        if (!$canReadInactive) {
            $this->query = $this->query->where('is_enonce_active', 1);
        }
        return $this;
    }

    public function with(array $deps)
    {
        $this->query = $this->query->with($deps);
        return $this;
    }
}
