<?php

namespace Tests\Feature\Student;

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
        ];

        $response = $this->actingAs($student->user)->put(
            route('students.update', ["student" => $student->id]),
            $data
        );

        $response
            ->assertStatus(Response::HTTP_CREATED);

        $student = Student::find($student->id);
        $this->assertTrue($student->user->firstname == $data['firstname']);
        $this->assertTrue($student->user->username == $data['username']);
    }

    /** @test **/
    public function a_student_can_not_update_an_other_profil()
    {
        //$this->withoutExceptionHandling();

        $this->createStudent(2);
        $students = Student::all();
        $response = $this->actingAs($students[0]->user)->put(
            route('students.update', ["student" =>  $students[1]->id]),
            ["lastname" => "lastname 1"]
        );

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
