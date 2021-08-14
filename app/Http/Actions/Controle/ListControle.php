<?php

namespace App\Http\Actions\Controle;

use App\Controle;
use App\Http\Actions\Queries;
use App\Constants\CodeReferentiel;
use App\Http\Actions\Content\DataAccess;
use App\Http\Actions\Checker\UserChecker;
use App\Http\Actions\Content\ReadContent;
use App\Http\Actions\MatiereTeacher\FindTeacherPrincipal;

class ListControle
{

    private UserChecker $userChecker;
    private ReadContent $readContent;
    private DataAccess $dataAccess;
    private FindTeacherPrincipal $findTeacherPrincipal;

    public function __construct(
        UserChecker $userChecker,
        ReadContent $readContent,
        DataAccess $dataAccess,
        FindTeacherPrincipal $findTeacherPrincipal
    ) {
        $this->userChecker = $userChecker;
        $this->readContent = $readContent;
        $this->dataAccess = $dataAccess;
        $this->findTeacherPrincipal = $findTeacherPrincipal;
    }

    public function execute(string $type, array $params)
    {
        $base = $type === CodeReferentiel::EXAMEN
            ? $this->examens($params)
            : $this->partiels($params);

        $canReadInactive = $this->userChecker->canReadInactive($base['teacher']);

        $controles = Queries::of($base['query'])
            ->addMatiere($params)
            ->addClasse($params)
            ->addTypeControle($type)
            ->addEnonceActive($canReadInactive)
            ->with(['type', 'trimestre', 'session'])
            ->get();

        $canAccessContent = $this->dataAccess->canAccessContent($base['teacher'], $params);

        $listControles = $controles->map(
            fn ($controle) => $this->readContent
                ->byControle($controle, $canAccessContent)
        );

        if ($canAccessContent) {
            return $listControles;
        }

        // traiter les cas où la correction est inactif
        return $listControles
            ->map(function ($exercise) use ($canReadInactive) {
                if (!$canReadInactive && !$exercise->is_correction_active) {
                    $exercise->correction = "Ce contenu est inactif.";
                }
                return $exercise;
            });
    }

    private function examens(array $params = [])
    {
        $teacher = $this->findTeacherPrincipal->execute($params['matiere'], $params['classe']);
        if (!isset($teacher)) {
        }
        $query = Controle::where('teacher_id', $teacher->id)->orderBy('year', 'desc');
        return ['query' => $query, 'teacher' => $teacher->id];
    }

    private function partiels(array $params)
    {
        $query = Controle::where('teacher_id', $params['teacher'])
            ->orderBy('position', 'asc');
        if (isset($params['year'])) {
            $query = $query->where('year', $params['year']);
        }
        if (isset($params['trimestre'])) {
            $query = $query->where('trimestre_id', $params['trimestre']);
        }
        return ['query' => $query, 'teacher' => $params['teacher']];
    }
}
