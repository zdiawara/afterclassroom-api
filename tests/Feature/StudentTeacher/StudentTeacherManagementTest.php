<?php

namespace Tests\Feature\StudentTeacher;

use App\Classe;
use App\Matiere;
use App\Student;
use App\Teacher;
use Tests\TestCase;
use App\Referentiel;
use App\StudentTeacher;
use App\TeacherMatiere;
use App\Constants\CodeReferentiel;
use App\Constants\TypeReferentiel;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StudentTeacherManagementTest extends TestCase
{
    use RefreshDatabase;


    /** @test **/
    public function a_student_can_follow_a_teacher()
    {
        $this->withoutExceptionHandling();

        $classe = factory(Classe::class)->create();
        $this->makeStudent($classe);
        $this->createTeacher();

        $student = Student::first();

        $teacher = Teacher::first();
        $matiereId = $teacher->matieres->random()->id;
        $classeId = $classe->id;
        $enseignement = CodeReferentiel::BASIC;

        $response = $this->actingAs($student->user)
            ->post(
                route('students.teachers.store', ['student' => $student->id]),
                [
                    'teacher' => $teacher->id,
                    'matiere' => $matiereId,
                    'classe' => $classeId,
                    'enseignement' => $enseignement,
                ]
            );

        $response
            ->assertStatus(Response::HTTP_CREATED);

        $created = StudentTeacher::first();
        $this->assertTrue($created->teacher_id == $teacher->id);
        $this->assertTrue($created->classe_id == $classeId);
        $this->assertTrue($created->matiere_id == $matiereId);
        $this->assertTrue($created->collegeYear->etat_id == CodeReferentiel::IN_PROGRESS);
    }
}
