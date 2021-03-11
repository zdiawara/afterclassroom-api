<?php

namespace App\Http\Actions\Teacher;

use App\Classe;
use App\Matiere;
use App\Http\Actions\Checker\UserChecker;
use Symfony\Component\HttpFoundation\Request;


class CountTeacherEnseignement{

    private $userChecker;

    public function __construct(UserChecker $userChecker)
    {
        $this->userChecker = $userChecker;
    }
    
    private function buildCount($params=[],$query,$prefix=null){
        foreach ($params as $key => $value) {
            $field = $prefix ? $prefix.'.'.$key : $key;
            $query->where($field,$value);
        }
    }

    public function execute(Request $request){

        $params = [];
        
        if($request->has('classe')){
            $params['classe_id'] = Classe::where('code',$request->get('classe'))->firstOrFail()->id;
        }
        if($request->has('matiere')){
            $params['matiere_id'] = Matiere::where('code',$request->get('matiere'))->firstOrFail()->id;
        }

        $canReadInactive = false;//$this->userChecker->canReadInactive($username);

        return [
            'exercises' => function ($query) use ($params,$canReadInactive) {
                $this->buildCount($params,$query,'chapters');
                if(!$canReadInactive){
                    $query->where('exercises.active_enonce',1);
                }
            },
            'chapters' => function ($query) use ($params,$canReadInactive) {
                $this->buildCount($params,$query);
                if(!$canReadInactive){
                    $query->where('active',1);
                }
            },
            'controles' => function ($query) use ($params,$canReadInactive) {
                $this->buildCount($params,$query);
                if(!$canReadInactive){
                    $query->where('active_enonce',1);
                }
            },
            'students' => function ($query) use ($params) {
                if(isset($params['classe_id'])){
                    $query->where('student_teacher.classe_id',$params['classe_id']);
                }
            }
        ];
    }

}