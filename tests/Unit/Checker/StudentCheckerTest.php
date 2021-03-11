<?php

namespace Tests\Unit\Checker;

use App\Classe;
use App\Student;
use App\Teacher;

use Tests\TestCase;
use App\Exceptions\PrivilegeException;
use App\Http\Actions\Checker\UserChecker;
use App\Http\Actions\Checker\StudentChecker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StudentCheckerTest extends TestCase
{
    use RefreshDatabase;
    
    
    /** @test **/
    public function a_student_can_subcribe_in_a_classe()
    {
        $this->createStudent();
        $student = Student::first();
        
        $result = (new StudentChecker)->canSubcribeClasse($student,$student->classe);

        $this->assertTrue($result);
    }

    /** @test **/
    public function a_student_can_not_subcribe_in_an_other_classe()
    {   
        $this->createStudent();
        $student = Student::first();
        
        $this->expectException(PrivilegeException::class);
        $result = (new StudentChecker)->canSubcribeClasse($student,\factory(Classe::class)->create());
    }

}
