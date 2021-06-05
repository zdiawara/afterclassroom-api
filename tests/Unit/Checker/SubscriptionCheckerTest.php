<?php

namespace Tests\Unit\Checker;

use App\Classe;
use App\Student;

use Tests\TestCase;
use App\Exceptions\PrivilegeException;
use App\Http\Actions\Checker\StudentChecker;
use App\Http\Actions\Student\FindStudentClasse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Actions\CollegeYear\CollegeYearInProgress;

class SubscriptionCheckerTest extends TestCase
{
    use RefreshDatabase;


    /** @test **/
    public function a_student_can_subcribe_in_a_classe()
    {
        $this->createStudent();
        $student = Student::first();

        $result = (new StudentChecker(new FindStudentClasse(new CollegeYearInProgress)))->canSubcribeClasse($student, $student->classes->first());

        $this->assertTrue($result);
    }

    /** @test **/
    public function a_student_can_not_subcribe_in_an_other_classe()
    {
        $this->createStudent();
        $student = Student::first();

        $this->expectException(PrivilegeException::class);
        (new StudentChecker(new FindStudentClasse(new CollegeYearInProgress)))
            ->canSubcribeClasse($student, factory(Classe::class)->create());
    }
}
