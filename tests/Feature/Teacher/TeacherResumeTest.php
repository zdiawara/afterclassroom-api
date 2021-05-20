<?php

namespace Tests\Feature\Teacher;

use App\User;
use App\Teacher;
use Tests\TestCase;
use App\Referentiel;
use App\Http\Resources\TeacherResource;
use App\Http\Resources\ReferentielResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeacherResumeTest extends TestCase
{
    use RefreshDatabase;


    /** @test **/
    public function a_teacher_resume_can_be_show_for_a_classe()
    {
        

    }

}
