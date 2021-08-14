<?php

namespace App\Http\Controllers\Api;

use App\Teacher;
use App\Http\Controllers\Controller;

use App\Http\Requests\TeacherWriterRequest;
use App\Http\Resources\TeacherWriterResource;
use App\Http\Resources\WriterResource;
use App\TeacherWriter;
use App\Writer;

class TeacherWriterController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:api');
        // $this->middleware('role:teacher');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Teacher $teacher)
    {
        return WriterResource::collection(
            $teacher->writers()
                ->get()
        );
    }


    public function store(TeacherWriterRequest $request, Teacher $teacher)
    {
        $writerId = $request->get('writer');

        $teacherWriter = TeacherWriter::where('teacher_id', $teacher->id)
            ->where('writer_id', $writerId)
            ->first();

        if (!is_null($teacherWriter)) {
            return $this->conflictResponse("Ce rédacteur a été déjà ajouté.");
        }

        $teacherWriter = TeacherWriter::firstOrCreate([
            'teacher_id' => $teacher->id,
            'writer_id' => $writerId,
        ]);

        $teacherWriter->load(['writer', 'teacher']);

        return $this->createdResponse(new TeacherWriterResource($teacherWriter));
    }

    public function destroy(Teacher $teacher, Writer $writer)
    {
        $teacher->writers()->detach($writer->id);
        return $this->deletedResponse();
    }
}
