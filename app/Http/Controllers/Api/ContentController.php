<?php

namespace App\Http\Controllers\Api;

use App\Chapter;
use App\Content;
use DOMDocument;
use App\Controle;
use App\Exercise;
use App\Solution;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContentRequest;
use App\Http\Resources\ContentResource;
use App\Http\Actions\Checker\EnseignementChecker;
use App\Http\Actions\Checker\TeacherMatiereChecker;


class ContentController extends Controller
{
    private $enseignementChecker;

    private $teacherMatiereChecker;

    public function __construct(EnseignementChecker $enseignementChecker,TeacherMatiereChecker $teacherMatiereChecker)
    {
        $this->middleware(['auth:api']);
        $this->middleware('role:teacher',['except' => ['index','show']]);
        $this->enseignementChecker = $enseignementChecker;
        $this->teacherMatiereChecker = $teacherMatiereChecker;
    }  

    public function update(ContentRequest $request, Content $content)
    {
        $enseignement = $content->contentable;
        
        // Verifie que l'ut connecté peut modifier le contenu
        $this->enseignementChecker->canUpdate($enseignement);

        $fields = $request->only(['data','active']);

        $exercisable = null;

        
        if($enseignement instanceof Exercise){
            $exercisable = $enseignement->exercisable;
        }else if($enseignement instanceof Solution){
            if(isset($enseignement->exercise_id)){
                $exercisable = $enseignement->exercise->exercisable;
            }else if(isset($enseignement->controle_id)){
                $exercisable = $enseignement->controle;
            }
        }
       
        if(isset($fields['active'])){
            $this->teacherMatiereChecker->canTeach(
                $exercisable ? $exercisable->teacher : $enseignement->teacher,
                $exercisable ? $exercisable->matiere : $enseignement->matiere
            );
        }else{
            $this->teacherMatiereChecker->canEdit(
                $exercisable ? $exercisable->teacher : $enseignement->teacher,
                $exercisable ? $exercisable->matiere : $enseignement->matiere
            );
        }
        
        // Mets à jour le contenu
        $content->update($fields);
        
        // Active le controle ou le chapitre selon l'état du contenu
        if(isset($fields['active']) && ($enseignement instanceof Exercise && ($exercisable instanceof Chapter) || $enseignement instanceof Controle)){
            $enseignement->update([
                'active' => $fields['active']
            ]);
        }
        

        return $this->createdResponse(new ContentResource($content));
    }
}
