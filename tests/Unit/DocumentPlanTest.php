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
            <h2>Titre 1</h2>
            <h3>Titre 1.1</h3>
            <h3>Titre 1.2</h3>
            <h2>Titre 2</h2>
            <h2>Titre 3</h2>
            <h3>Titre 3.1 </h3>
        </div>');

        $this->assertTrue($plan == "<h2>Titre 1</h2><h2>Titre 2</h2><h2>Titre 3</h2>");
        $this->assertTrue((new DocumentPlan())->execute(null) == "");
    }
}
