<?php

namespace Tests\Unit;

use App\Chapter;
use App\Matiere;
use App\Teacher;

use Tests\TestCase;
use App\Exceptions\NotFoundException;
use App\Exceptions\PrivilegeException;
use App\Http\Actions\User\UserChecker;
use App\Http\Resources\ChapterResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserChekerTest extends TestCase
{
    use RefreshDatabase;
}
