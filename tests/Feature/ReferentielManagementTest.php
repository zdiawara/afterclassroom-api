<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Referentiel;
use App\Http\Resources\ReferentielResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReferentielManagementTest extends TestCase
{
    use RefreshDatabase;


    /** @test **/
    /** @ignore **/
    public function a_referentiel_can_be_create()
    {

        $referentiel = factory(Referentiel::class)->make();

        $user = $this->createAdmin()->user;

        $response = $this->actingAs($user)
            ->post(route('referentiels.store'), $referentiel->toArray());

        $referentiel->id = 1;
        $response
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonFragment($this->decodeResource(new ReferentielResource($referentiel)));
    }


    /** @test **/
    /** @ignore **/
    public function a_referentiel_can_be_update()
    {
        $user = $this->createAdmin()->user;

        $this->actingAs($user)->post(route('referentiels.store'), factory(Referentiel::class)->make()->toArray());

        $referentiel = Referentiel::first();
        $referentiel->name = "New name";
        $referentiel->id = "test";
        $referentiel->type = "new type";

        $response = $this->actingAs($user)->put(
            route('referentiels.update', ["referentiel" => $referentiel->id]),
            $referentiel->toArray()
        );

        $response
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson($this->decodeResource(new ReferentielResource($referentiel)));
    }
}
