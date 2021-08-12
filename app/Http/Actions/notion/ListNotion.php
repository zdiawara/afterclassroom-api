<?php

namespace App\Http\Actions\Notion;

use App\Notion;
use App\Http\Actions\Queries;
use App\Http\Actions\Checker\UserChecker;

class ListNotion
{

    private UserChecker $userChecker;

    public function __construct(UserChecker $userChecker)
    {
        $this->userChecker = $userChecker;
    }

    public function execute(string $teacher, array $params = [])
    {
        $query = Notion::with(['specialite']);

        if (!$this->userChecker->canReadInactive($teacher)) {
            $query = $query->where('is_active', 1);
        }

        return Queries::of($query)
            ->addMatiere($params)
            ->addSpecialite($params)
            ->addClasse($params)
            ->orderByPosition()
            ->get();
    }
}
