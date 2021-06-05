<?php

namespace App\Http\Controllers;

use App\Classe;
use App\Exceptions\BadRequestException;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function extractContent($request)
    {
        $data = [];
        if ($request->has("content")) {
            $content = $request->get("content");
            if (isset($content['data'])) {
                $data['content'] = $content['data'];
            }
            if (isset($content['active'])) {
                $data['active'] = $content['active'];
            }
        }
        return $data;
    }

    protected function getExerciseContent($request, $field)
    {
        $response = [];
        if ($request->has($field)) {
            $content = $request->get($field);
            if (!isset($content['data']) && !isset($content['active'])) {
                throw new BadRequestException("Error formattage du champs " . $field);
            }
            if (isset($content['data'])) {
                $response[$field] = $content['data'];
            }

            if (isset($content['active'])) {
                $response['is_' . $field . '_active'] = $content['active'];
            }
        }
        return $response;
    }

    protected function extractExerciseFields(Request $request)
    {

        $fields =  array_merge(
            $request->only(['prerequis', 'notions']),
            $this->getExerciseContent($request, 'enonce'),
            $this->getExerciseContent($request, 'correction')
        );

        if ($request->has('type')) {
            $fields['type_id'] = $request->get('type');
        }
        if ($request->has('chapter')) {
            $fields['chapter_id'] = $request->get('chapter');
        }
        return $fields;
    }

    protected function extractQuestionFields(Request $request)
    {

        $fields =  array_merge(
            $request->only(['title']),
            $this->extractContent($request),
        );

        if ($request->has('chapter')) {
            $fields['chapter_id'] = $request->get('chapter');
        }
        return $fields;
    }

    protected function extractControleFields(Request $request)
    {

        $fields =  array_merge(
            $request->only(['year']),
            $this->getExerciseContent($request, 'enonce'),
            $this->getExerciseContent($request, 'correction')
        );

        if ($request->has('type')) {
            $fields['type_id'] = $request->get('type');
        }
        if ($request->has('trimestre')) {
            $fields['trimestre_id'] = $request->get('trimestre');
        }
        if ($request->has('subject')) {
            $fields['subject_id'] = $request->get('subject');
        }
        return $fields;
    }

    protected function extractMatiere($request)
    {
        $fields = [];
        if ($request->has('matiere')) {
            $fields['matiere_id'] =  $request->get('matiere');
        }
        if ($request->has('specialite')) {
            $fields['specialite_id'] =  $request->get('specialite');
        }
        return $fields;
    }

    protected function extractClasse($request)
    {
        $fields = [];
        if ($request->has('classe')) {
            $fields['classe_id'] = $request->get('classe');
        }
        return $fields;
    }

    protected function extractClasses($request)
    {
        $classes = [];
        if ($request->has('classes')) {
            $classes = collect($request->get('classes'))->map(function ($classe) {
                return Classe::where("code", $classe)->firstOrFail()->id;
            });
        }
        return [
            'classes' => $classes
        ];
    }

    protected function extractTeacher($request)
    {
        if ($request->has('teacher')) {
            return ['teacher_id' => $request->get('teacher')];
        }
        return [];
    }

    protected function extractEnseignementFields(Request $request)
    {
        return array_merge(
            $this->extractMatiere($request),
            $this->extractClasse($request),
            $this->extractTeacher($request)
        );
    }


    /**
     * 
     */
    protected function createdResponse($data, $message = null)
    {
        $json = [
            "data" => $data
        ];
        if ($message) {
            $json['message'] = $message;
        }
        return response()->json($json, Response::HTTP_CREATED);
    }

    /**
     * 
     */
    protected function deletedResponse()
    {
        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    /**
     * 
     */
    protected function conflictResponse($message = null)
    {
        return response()->json([
            "message" => $message
        ], Response::HTTP_CONFLICT);
    }

    protected function inactiveResponse($message = null)
    {
        return response()->json([
            "message" => "Cette résource n'est pas encore activée !"
        ], Response::HTTP_CONFLICT);
    }
}
