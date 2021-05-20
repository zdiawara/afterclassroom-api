<?php

namespace Tests\Feature\StudentTeacher;

use App\Classe;
use App\Student;
use App\Teacher;
use Tests\TestCase;
use App\StudentTeacher;
use App\Constants\CodeReferentiel;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StudentTeacherManagementTest extends TestCase
{
    use RefreshDatabase;


    /** @test **/
    public function a_student_can_add_a_teacher()
    {
        $this->withoutExceptionHandling();
        
        $this->createStudent();
        
        $studentTeacher = factory(StudentTeacher::class)->make();
        $student = Student::first();
        $student->update([
            'classe_id' => $studentTeacher->classe
        ]);

        $response = $this->actingAs($student->user)
            ->post(route('students.teachers.store',['student' => $student->id ]),
            $studentTeacher->toArray()
        );
        
        $response
            ->assertStatus(Response::HTTP_CREATED);

        $created = StudentTeacher::first();
        $this->assertTrue($created->teacher->id==$studentTeacher->teacher);
        $this->assertTrue($created->classe->id==$studentTeacher->classe);
        $this->assertTrue($created->matiere->id==$studentTeacher->matiere);
        $this->assertTrue($created->collegeYear->etat->code==CodeReferentiel::IN_PROGRESS);
    }

    /** @test **/
    public function a_teacher_can_not_add_matiere_to_teach_if_already_he_teach_it()
    {
        //$this->withoutExceptionHandling();

        $teacher = factory(Teacher::class)->create();

        $matiere = factory(Matiere::class)->create();

        $teacher->matieres()->attach($matiere->id,[
            "etat_id" => factory(Referentiel::class)->create(['type' => TypeReferentiel::ETAT ])->id,
            "justificatif" => "test.pdf"
        ]);

        $teacherMatiere = factory(MatiereTeacher::class)->make(["matiere"=>$matiere->id ]);

        $response = $this->actingAs($teacher->user)
            ->post(route('teachers.matieres.store',['teacher' => $teacher->id]),$teacherMatiere->toArray());
        
        $response
            ->assertStatus(Response::HTTP_CONFLICT);
    }

    /** @test **/
    public function a_teacher_can_delete_her_matiere()
    {
        $this->withoutExceptionHandling();

        $teacher = factory(Teacher::class)->create();
        $matiere = factory(Matiere::class)->create();

        $teacher->matieres()->attach($matiere->id,[
            "etat_id" => factory(Referentiel::class)->create(['type' => TypeReferentiel::ETAT ])->id,
            "justificatif" => "test.pdf"
        ]);

        $response = $this->actingAs($teacher->user)
            ->delete(route('teachers.matieres.destroy',['teacher' => $teacher->id,'matiere'=>$matiere->id]));

        $response
            ->assertStatus(Response::HTTP_NO_CONTENT);
    }



}
