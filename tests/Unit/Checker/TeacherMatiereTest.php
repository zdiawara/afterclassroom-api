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
        $teacher = \factory(Teacher::class)->create();
        
        $canTeach = (new TeacherMatiereChecker)->canEdit($teacher,$teacher->matieres()->first());

        $this->assertTrue($canTeach);
    }

    
    /** @test **/
    
    public function a_teacher_can_delete_her_teaching_matiere()
    {
        $teacher = \factory(Teacher::class)->create();
        $this->actingAs($teacher->user);
        $this->assertTrue((new TeacherMatiereChecker)->canDelete($teacher, $teacher->matieres()->first()));
    }

    /** @test **/
    public function not_found_exception_if_teacher_matiere_not_founded()
    {
        $teacher = \factory(Teacher::class)->create();
        $this->actingAs($teacher->user);
        $this->expectException(NotFoundException::class);
        (new TeacherMatiereChecker)->canDelete($teacher, \factory(Matiere::class)->create());
    }

    /** @test **/
    public function privilege_exception_if_user_can_not_delete_teacher_matiere()
    {
        $this->expectException(PrivilegeException::class);
        
        $teacher = \factory(Teacher::class)->create();
        $this->actingAs($teacher->user);
        
        $anOtherTeacher = \factory(Teacher::class)->create();
        (new TeacherMatiereChecker)->canDelete($anOtherTeacher,$anOtherTeacher->matieres()->first());
    }


}
