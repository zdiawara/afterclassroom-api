<?php

namespace Tests\Unit;

use App\Referentiel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        Referentiel::all();
        $this->assertTrue(true);
    }
}
