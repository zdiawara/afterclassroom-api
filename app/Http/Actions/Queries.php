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

    // public function addActive($query, $canReadInactive)
    // {
    //     if (!$canReadInactive) {
    //         return $query->where('is_active', 1);
    //     }
    //     return $query;
    // }

    // public function buildCommonQuery($query, $request)
    // {
    //     $page = $request->get('page', 1);

    //     $newQuery = $query;

    //     if ($request->has('matiere')) {
    //         $newQuery = $this->whereHas($query, 'matiere', $request->get('matiere'));
    //     }
    //     if ($request->has('specialite')) {
    //         $newQuery = $this->whereHas($query, 'specialite', $request->get('specialite'));
    //     }

    //     return [
    //         'query' => $newQuery,
    //         'page' => $page
    //     ];
    // }

    // public function buildQuery($query, $request)
    // {
    //     $result = $this->buildCommonQuery($query, $request);

    //     if ($request->has('classe')) {
    //         $result['query'] = $this->whereHas($result['query'], 'classe', $request->get('classe'));
    //     }

    //     return $result;
    // }

    // public function bookQuery($query, $request)
    // {

    //     $classe = $request->get('classe');

    //     $result = $this->buildCommonQuery($query, $request);

    //     if (isset($classe)) {
    //         $result['query'] = $this->whereHas($result['query'], 'classes', $classe);
    //     }

    //     return $result;
    // }
}
