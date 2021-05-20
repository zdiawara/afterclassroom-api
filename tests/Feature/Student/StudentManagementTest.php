<?php

namespace Tests\Feature\Student;

use App\Classe;
use App\Student;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StudentManagementTest extends TestCase
{
    use RefreshDatabase;


    /** @test **/
    public function a_student_can_be_create()
    {
        $this->withoutExceptionHandling();

        $response = $this->createStudent();

        $response
            ->assertStatus(Response::HTTP_CREATED);
            //->assertJsonFragment($this->decodeResource(new TeacherResource($teacher)));
    }


    /** @test **/
    public function a_student_can_update_her_profil()
    {
        $this->withoutExceptionHandling();

        $this->createStudent();

        $student = Student::first();
        
        $data = [
            "firstname" => "Prenom 1",
            "username" => 'zdiawara',
            "classe" => \factory(Classe::class)->create()->id
        ];
        
        $response = $this->actingAs($student->user)->put(
            route('students.update',["student"=>$student->id]),
            $data
        );

        $response
            ->assertStatus(Response::HTTP_CREATED);
        
        $student = Student::first();
        $this->assertTrue($student->user->firstname==$data['firstname']);
        $this->assertTrue($student->user->username==$data['username']);
        $this->assertTrue($student->classe->id==$data['classe']);
    }

    /** @test **/
    public function a_student_can_not_update_an_other_profil()
    {
        //$this->withoutExceptionHandling();

        $this->createStudent(2);
        
        $response = $this->actingAs(Student::find(2)->user)->put(
            route('students.update',["student"=>Student::first()->id]),
            ["lastname"=>"lastname 1"]
        );

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    
    }


}
