<?php

namespace Tests\Unit\Checker;

use App\Matiere;
use App\Teacher;
use Tests\TestCase;
use App\Exceptions\NotFoundException;
use App\Exceptions\PrivilegeException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Actions\Checker\TeacherMatiereChecker;


class TeacherMatiereTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_teacher_can_teach_a_valide_matiere()
    {
        $this->createTeacher();
        $teacher = Teacher::first();
        $canTeach = (new TeacherMatiereChecker)->canEdit($teacher->id, $teacher->matieres->first()->id);
        $this->assertTrue($canTeach);
    }


    /** @test **/

    public function a_teacher_can_delete_her_teaching_matiere()
    {
        $this->createTeacher();
        $teacher = Teacher::first();
        $this->actingAs($teacher->user);
        $this->assertTrue((new TeacherMatiereChecker)->canDelete($teacher->id, $teacher->matieres->first()->id));
    }

    /** @test **/
    public function not_found_exception_if_teacher_matiere_not_founded()
    {
        $this->createTeacher();
        $teacher = Teacher::first();
        $this->actingAs($teacher->user);
        $this->expectException(NotFoundException::class);
        (new TeacherMatiereChecker)->canDelete($teacher, \factory(Matiere::class)->create());
    }

    /** @test **/
    public function privilege_exception_if_user_can_not_delete_teacher_matiere()
    {
        $this->expectException(PrivilegeException::class);

        $this->createTeacher();
        $this->createTeacher();

        $teachers = Teacher::all();
        $this->actingAs($teachers[0]->user);

        $anOtherTeacher = $teachers[1];
        (new TeacherMatiereChecker)->canDelete($anOtherTeacher->id, $anOtherTeacher->matieres->first()->id);
    }
}
