<?php

namespace Tests\Feature;

use App\Matiere;
use App\Teacher;
use Tests\TestCase;
use App\Referentiel;
use App\TeacherMatiere;
use App\Constants\CodeReferentiel;
use App\Constants\TypeReferentiel;
use App\Http\Resources\TeacherResource;
use App\Http\Resources\TeacherMatiereResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeacherMatiereManagementTest extends TestCase
{
    use RefreshDatabase;


    /** @test **/
    public function a_teacher_can_add_a_matiere_to_teach()
    {
        $this->withoutExceptionHandling();

        factory(Referentiel::class)->create(["code" => CodeReferentiel::VALIDATING, 'type' => TypeReferentiel::ETAT]);

        $teacher = factory(Teacher::class)->create();

        $teacherMatiere = factory(TeacherMatiere::class)->make([
            'type' => TypeReferentiel::ETAT
        ]);

        $response = $this->actingAs($teacher->user)
            ->post(route('teachers.matieres.store', ['teacher' => $teacher->id]), $teacherMatiere->toArray());

        $response
            ->assertStatus(Response::HTTP_CREATED);
    }

    /** @test **/
    public function a_teacher_can_not_add_matiere_to_teach_if_already_he_teach_it()
    {
        //$this->withoutExceptionHandling();

        $teacher = factory(Teacher::class)->create();

        $matiere = factory(Matiere::class)->create();

        $teacher->matieres()->attach($matiere->id, [
            "etat_id" => factory(Referentiel::class)->create(['type' => TypeReferentiel::ETAT])->id,
            "justificatif" => "test.pdf"
        ]);

        $teacherMatiere = factory(TeacherMatiere::class)->make(["matiere" => $matiere->id]);

        $response = $this->actingAs($teacher->user)
            ->post(route('teachers.matieres.store', ['teacher' => $teacher->id]), $teacherMatiere->toArray());

        $response
            ->assertStatus(Response::HTTP_CONFLICT);
    }

    /** @test **/
    public function a_teacher_can_delete_her_matiere()
    {
        $this->withoutExceptionHandling();

        $teacher = factory(Teacher::class)->create();
        $matiere = factory(Matiere::class)->create();

        $teacher->matieres()->attach($matiere->id, [
            "etat_id" => factory(Referentiel::class)->create(['type' => TypeReferentiel::ETAT])->id,
            "justificatif" => "test.pdf"
        ]);

        $response = $this->actingAs($teacher->user)
            ->delete(route('teachers.matieres.destroy', ['teacher' => $teacher->id, 'matiere' => $matiere->id]));

        $response
            ->assertStatus(Response::HTTP_NO_CONTENT);
    }
}
