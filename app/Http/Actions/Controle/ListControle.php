<?php

namespace App\Http\Actions\Controle;

use App\Controle;
use App\Http\Actions\Queries;
use App\Constants\CodeReferentiel;
use Illuminate\Support\Facades\DB;
use App\Http\Actions\Content\DataAccess;
use App\Http\Actions\Checker\UserChecker;
use App\Http\Actions\Content\ReadContent;
use App\Http\Actions\TeacherMatiere\FindTeacherPrincipal;

class ListControle
{

    private UserChecker $userChecker;
    private ReadContent $readContent;
    private DataAccess $dataAccess;
    private FindTeacherPrincipal $findTeacherPrincipal;

    public function __construct(UserChecker $userChecker, ReadContent $readContent, DataAccess $dataAccess, FindTeacherPrincipal $findTeacherPrincipal)
    {
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
            ->with(['type', 'trimestre'])
            ->get();

        $canReadContent = $this->dataAccess->canReadContent($base['teacher'], $params);

        $listControles = $controles->map(
            fn ($controle) => $this->readContent
                ->byControle($controle, $canReadContent)
        );

        if ($canReadContent) {
            return $listControles;
        }

        // traiter les cas où la correction est inactifs
        return $listControles
            ->map(function ($exercise) {
                if (!$exercise->is_correction_active) {
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
        return ['query' => $query, 'teacher' => $teacher->user->username];
    }

    private function partiels(array $params)
    {
        $query = Controle::whereHas('teacher.user', function ($q) use ($params) {
            $q->where(DB::raw('lower(users.username)'), strtolower($params['teacher']));
        })->whereHas('trimestre', function ($q) use ($params) {
            $q->where('code', $params['trimestre']);
        })->orderBy('position', 'asc');


        return ['query' => $query, 'teacher' => $params['teacher']];
    }
}
