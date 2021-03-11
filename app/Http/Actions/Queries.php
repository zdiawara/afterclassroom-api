<?php

namespace App\Http\Actions;

class Queries
{


    public function whereHas($query, $relation, $code)
    {
        return $query->whereHas($relation, function ($q) use ($code) {
            $q->where('code', $code);
        });
    }

    public function addActive($query, $canReadInactive)
    {
        if (!$canReadInactive) {
            return $query->where('active', 1);
        }
        return $query;
    }

    public function buildCommonQuery($query, $request)
    {
        $page = $request->get('page', 1);

        $newQuery = $query;

        if ($request->has('matiere')) {
            $newQuery = $this->whereHas($query, 'matiere', $request->get('matiere'));
        }
        if ($request->has('specialite')) {
            $newQuery = $this->whereHas($query, 'specialite', $request->get('specialite'));
        }

        return [
            'query' => $newQuery,
            'page' => $page
        ];
    }

    public function buildQuery($query, $request)
    {
        $result = $this->buildCommonQuery($query, $request);

        if ($request->has('classe')) {
            $result['query'] = $this->whereHas($result['query'], 'classe', $request->get('classe'));
        }

        return $result;
    }

    public function bookQuery($query, $request)
    {

        $classe = $request->get('classe');

        $result = $this->buildCommonQuery($query, $request);

        if (isset($classe)) {
            $result['query'] = $this->whereHas($result['query'], 'classes', $classe);
        }

        return $result;
    }
}
