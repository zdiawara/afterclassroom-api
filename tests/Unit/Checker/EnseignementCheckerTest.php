<?php

namespace Tests\Unit\Checker;

use App\Chapter;
use App\Teacher;
use Tests\TestCase;
use App\Exceptions\PrivilegeException;
use App\Http\Actions\User\UserChecker;
use App\Http\Actions\Checker\EnseignementChecker;
use Illuminate\Foundation\Testing\RefreshDatabase;


class EnseignementCheckerTest extends TestCase
{
    use RefreshDatabase;
   
    /** 
     * Un enseignement peut modifier son enseignement
     * @test 
     **/
    public function a_teacher_can_update_her_enseignement()
    {
        $this->withoutExceptionHandling();
        
        $result = $this->createChapter();

        // Se connecter avec le teacher du chapter
        $this->actingAs($result['teacher']->user);
        
        $canUpdate = (new EnseignementChecker)->canUpdate(Chapter::first());

        $this->assertTrue($canUpdate);
    }

    /** @test **/
    public function privilege_exception_if_teacher_update_an_onther_enseignement()
    {
        $result = $this->createChapter();
        
        // Chapitre appartenant a un autre teacher
        $chapter = Chapter::first();
        $chapter->title = 'titre 1';

        // Se connecter avec un autre teacher
        $teacher = factory(Teacher::class)->create();
        $this->actingAs($teacher->user);
        
        $this->expectException(PrivilegeException::class);

        (new EnseignementChecker)->canUpdate($chapter);
    }

}
