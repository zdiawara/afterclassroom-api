<?php

namespace Tests\Unit\Checker;

use App\Http\Actions\Content\DocumentPlan;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;


class DocumentPlanTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function build_document_plan()
    {

        $plan = (new DocumentPlan())->execute('<div>
            <h1>Titre 1</h1>
            <h2>Titre 1.1</h2>
            <h2>Titre 1.2</h2>
            <h1>Titre 2</h1>
            <h1>Titre 3</h1>
            <h2>Titre 3.1 </h2>
        </div>');

        $this->assertTrue($plan == "<h1>Titre 1</h1><h1>Titre 2</h1><h1>Titre 3</h1>");
        $this->assertTrue((new DocumentPlan())->execute(null) == "");
    }
}
