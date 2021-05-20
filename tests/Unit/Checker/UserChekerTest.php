<?php

namespace Tests\Unit\Checker;

use App\Teacher;
use Tests\TestCase;
use App\Exceptions\PrivilegeException;

use App\Http\Actions\Checker\UserChecker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserChekerTest extends TestCase
{
    use RefreshDatabase;
    
    
    /** @test **/
    public function a_user_can_update_her_profil()
    {
        factory(Teacher::class)->create();

        $teacher = Teacher::first();
        
        $this->actingAs($teacher->user);
        
        $canEdit = (new UserChecker)->canUpdate($teacher);

        $this->assertTrue($canEdit);
    }

    /** @test **/
    public function a_user_can_not_update_an_other_profil()
    {   
        $this->actingAs(factory(Teacher::class)->create()->user);

        $this->expectException(PrivilegeException::class);

        (new UserChecker)->canUpdate(factory(Teacher::class)->create());
    }

}
