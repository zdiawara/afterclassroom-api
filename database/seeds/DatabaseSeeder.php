<?php

use App\User;
use App\Classe;
use App\Matiere;
use App\Student;
use App\Teacher;
use App\Category;
use App\Chapter;
use App\Controle;
use App\Exercise;
use App\Identify;
use App\Specialite;
use App\CollegeYear;
use App\Referentiel;
use App\StudentTeacher;
use App\ClasseMatiere;
use App\Country;
use Illuminate\Database\Seeder;
use App\Constants\CodeReferentiel;
use App\Constants\TypeReferentiel;
use App\Http\Actions\Content\DocumentPlan;
use App\Http\Actions\User\ManageIdentify;
use App\MatiereTeacher;
use App\Question;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Identify::create([
            'tranche' => 10,
            "current" => 0
        ]);

        $this->createReferentiels();

        Country::create([
            'name' => "Burkina Faso",
            'code' => "bf"
        ]);

        $classes = $this->generateClasses();

        /*$this->createSubject("Sujet type Bepc",CodeReferentiel::SUJET_TYPE_BEPC,'troisieme');
        $this->createSubject("Bepc",CodeReferentiel::BEPC,'troisieme');
        $this->createSubject("Sujet type Bac",CodeReferentiel::SUJET_TYPE_BAC,"terminale");
        $this->createSubject("Bac",CodeReferentiel::BAC,'terminale');*/

        $this->generateMatieres();

        $lycee = Referentiel::where('type', TypeReferentiel::LEVEL)
            ->where('code', CodeReferentiel::LYCEE)->first();

        $college = Referentiel::where('type', TypeReferentiel::LEVEL)
            ->where('code', CodeReferentiel::COLLEGE)->first();

        // Creation d'un teacher
        $t1 = $this->createTeacher('Yao', 'Lovie', 'lovie.yao@test.com', ['maths'], $lycee);
        $this->createTeacher('Alassane', 'Zerbo', 'zerbo.alassane@test.com',  ['maths'], $lycee);

        $t2 = $this->createTeacher('Souley', 'Drabo', 'souley.drabo@test.com', ['pc', 'maths'], $lycee);
        $t4 = $this->createTeacher('Joseph', 'Ibara', 'ibara.joseph@test.com', ['pc'], $lycee);

        $this->createTeacher('Sebastian', 'Mampassi', 'mampassi.sebastian@test.com', ['svt'], $lycee);
        $this->createTeacher('Baobab', 'Sidibé', 'sidibe.baobab@test.com',  ['francais'], $lycee);
        $this->createTeacher('Nafa', 'Traoré', 'traore.nafa@test.com',  ['hg'], $college);
        $this->createTeacher('Charles', 'Sanou', 'sanou.charles@test.com', ['hg'], $lycee);
        $this->createTeacher('Jean', 'Descartes', 'descartes.jean@test.com', ['philosophie'], $lycee);
        $this->createTeacher('Ouédraogo', 'Jean', 'ouedraogo.jean@test.com', ['philosophie'], $lycee);
        $this->createTeacher('Moussa', 'Coulibaly', 'coulibaly.moussa@test.com', ['anglais'], $college);
        $this->createTeacher('Sebastien', 'Zougrana', 'sebastien.zougrana@test.com', ['anglais'], $lycee);
        $this->createTeacher('Alassane', 'Drabo', 'alassane.drabo@test.com',  ['allemand'], $lycee);

        $s1 = $this->createStudent('Moussa', 'Ouattara', 'ouattara.moussa@test.com', 'terminale_d');
        $this->createStudent('Alassane', 'Traoré', 'traore.alassane@test.com', 'troisieme');
        $this->createStudent('Jean', 'Somé', 'some.jean@test.com', 'sixieme');

        $this->createStudentTeacher($s1, $t1, 'maths', 'terminale_d');
        $this->createStudentTeacher($s1, $t4, 'pc', 'terminale_d');

        $this->generateClasseMatieres();

        $this->createChapters();
        $this->createQuestions();
        $this->createExamens();
    }

    private function generateClasseMatieres()
    {
        $base = ['maths', 'francais', 'anglais'];

        $matiere_6_5 =  collect($base)->push('svt', 'hg',)->all();
        $matiere_4_3 = collect($matiere_6_5)->push('pc')->all();
        $matiere_c_d = collect($matiere_4_3)->push('philosophie')->all();
        $matiere_a = collect($base)->push('allemand')->push('philosophie')->all();

        $data = [
            'bf' => [
                'sixieme' => $matiere_6_5,
                'cinquieme' => $matiere_6_5,
                'quatrieme' => $matiere_4_3,
                'troisieme' => $matiere_4_3,

                'seconde_c' => $matiere_4_3,
                'premiere_c' => $matiere_c_d,
                'premiere_d' => $matiere_c_d,
                'terminale_c' => $matiere_c_d,
                'terminale_d' => $matiere_c_d,

                'seconde_a' => collect($matiere_a)->push('pc', 'svt'),
                'premiere_a' => collect($matiere_a)->push('pc', 'svt'),
                'terminale_a' => $matiere_a
            ],
        ];

        collect($data)->each(function ($item, $country) {
            collect($item)->each(function ($matieres, $classe) use ($country) {
                collect($matieres)->each(function ($matiere) use ($classe, $country) {
                    $_classe = Classe::where('code', $classe)->first();
                    $teacherId = null;
                    if ($_classe->has_faq == 1 || $_classe->is_exam_class == 1) {
                        $teacherId = MatiereTeacher::whereHas('level', function ($query) use ($_classe) {
                            if ($_classe->level->code == CodeReferentiel::LYCEE) {
                                $query->where('level_id', $_classe->level_id);
                            }
                        })
                            ->whereHas('matiere', function ($query) use ($matiere) {
                                $query->where('code', $matiere);
                            })->get()->random()->teacher_id;
                    }
                    $classeMatiere = new ClasseMatiere([
                        'classe_id' => $classe,
                        'matiere_id' => $matiere,
                        'country_id' => $country,
                        'teacher_id' =>  $teacherId,
                    ]);
                    $classeMatiere->save();
                });
            });
        });
    }

    private function createChapters()
    {

        MatiereTeacher::all()->each(function ($matiereTeacher) {
            Classe::all()->each(function ($classe) use ($matiereTeacher) {
                factory(Chapter::class, rand(2, 5))->make()->each(function ($chapter) use ($matiereTeacher, $classe) {
                    $chapter->teacher_id = $matiereTeacher->teacher->user->username;
                    $chapter->matiere_id = $matiereTeacher->matiere->code;
                    $chapter->classe_id = $classe->code;
                    $chapter->specialite_id = $this->getSpecialite($chapter->matiere_id);
                    $chapter->toc = (new DocumentPlan())->execute($chapter->content);
                    $chapter->save();

                    factory(Exercise::class, rand(0, 5))->make()->each(function ($exercise) use ($chapter) {
                        $exercise->chapter_id = $chapter->id;
                        $exercise->is_enonce_active = $exercise['enonce']['active'];
                        $exercise->enonce = $exercise['enonce']['data'];
                        $exercise->is_correction_active = $exercise['correction']['active'];
                        $exercise->correction = $exercise['correction']['data'];
                        $exercise->type_id = Referentiel::where('type', TypeReferentiel::EXERCISE)->get()->random()->code;

                        $exercise->save();
                    });
                });


                factory(Controle::class, rand(1, 5))->make()->each(function ($controle)  use ($matiereTeacher, $classe) {
                    $newControle = $this->buildControle($controle, $matiereTeacher->teacher, $matiereTeacher->matiere, $classe);

                    $newControle->type_id = Referentiel::where('type', TypeReferentiel::CONTROLE)
                        ->WhereIn('code', [CodeReferentiel::DEVOIR, CodeReferentiel::COMPOSITION])
                        ->get()
                        ->random()
                        ->code;

                    $newControle->trimestre_id = Referentiel::where('type', TypeReferentiel::TRIMESTRE)
                        ->get()
                        ->random()
                        ->code;

                    $newControle->save();
                });
            });
        });
    }

    private function createQuestions()
    {
        ClasseMatiere::whereNotNull('teacher_id')
            ->get()
            ->each(function ($classeMatiere) {
                Chapter::where('teacher_id', $classeMatiere->teacher_id)
                    ->where('matiere_id', $classeMatiere->matiere_id)
                    ->where('classe_id', $classeMatiere->classe_id)->get()
                    ->each(function ($chapter) {
                        factory(Question::class, rand(1, 5))
                            ->make()
                            ->each(function ($question) use ($chapter) {
                                $question->chapter_id = $chapter->id;
                                $question->save();
                            });
                    });
            });
    }

    private function createExamens()
    {
        ClasseMatiere::whereNotNull('teacher_id')
            ->whereHas('classe', function ($query) {
                $query->where('is_exam_class', 1);
            })
            ->get()
            ->each(function ($classeMatiere) {
                factory(Controle::class, rand(5, 6))->make()->each(function ($controle)  use ($classeMatiere) {

                    $newControle = $this->buildControle($controle, $classeMatiere->teacher, $classeMatiere->matiere, $classeMatiere->classe);

                    $newControle->type_id = Referentiel::where('type', TypeReferentiel::CONTROLE)
                        ->Where('code', CodeReferentiel::EXAMEN)
                        ->get()
                        ->random()
                        ->code;

                    $newControle->save();
                });
            });
    }


    private function buildControle(Controle $controle, Teacher $teacher, Matiere $matiere, Classe $classe)
    {
        $newControle = new Controle();
        $newControle->teacher_id = $teacher->user->username;
        $newControle->classe_id = $classe->code;
        $newControle->matiere_id = $matiere->code;
        $newControle->year = $controle->year;
        $newControle->is_public = $controle->is_public;
        $newControle->is_enonce_active = 1; // $controle['enonce']['active'];
        $newControle->enonce = $controle['enonce']['data'];
        $newControle->is_correction_active = 1; // $controle['correction']['active'];
        $newControle->correction = $controle['correction']['data'];

        return $newControle;
    }

    private function getSpecialite($matiere)
    {
        $specialites = Specialite::where('matiere_id', $matiere)->get();
        if ($specialites->count() != 0) {
            return $specialites->random()->code;
        }
        return null;
    }

    private function createStudentTeacher($student, $teacher, $matiere, $classe)
    {
        $st = new StudentTeacher();
        $st->matiere_id = Matiere::where('code', $matiere)->first()->id;
        $st->classe_id = Classe::where('code', $classe)->first()->id;
        $st->student_id = $student->userable->id;
        $st->teacher_id = $teacher->id;
        $st->enseignement_id = Referentiel::where('type', TypeReferentiel::ENSEIGNEMENT)
            ->where('code', CodeReferentiel::BASIC)
            ->firstOrFail()
            ->id;
        $st->college_year_id = CollegeYear::first()->id;
        $st->save();
    }

    private function createReferentiels()
    {

        $this->createReferentiel('Homme', 'homme', 'gender', 1);
        $this->createReferentiel('Femme', 'femme', 'gender', 2);

        ///// Niveau d'enseignement /////
        $secondaire =  $this->createReferentiel('Collège', CodeReferentiel::COLLEGE, TypeReferentiel::LEVEL, 1);
        $secondaire =  $this->createReferentiel('Collège et Lycée', CodeReferentiel::LYCEE, TypeReferentiel::LEVEL, 2);

        ////// categories de livres
        $this->createCategory('Annale', 'annale');
        $this->createCategory('Roman', 'roman');
        $this->createCategory('Livre', 'livre');

        ///// Ajout referentiel controle
        $this->createReferentiel('Devoir', CodeReferentiel::DEVOIR, TypeReferentiel::CONTROLE, 1);
        $this->createReferentiel('Composition', CodeReferentiel::COMPOSITION, TypeReferentiel::CONTROLE, 2);
        $this->createReferentiel('Examen', CodeReferentiel::EXAMEN, TypeReferentiel::CONTROLE, 3);

        $this->createReferentiel('1er trimestre', CodeReferentiel::TRIMESTRE_1, TypeReferentiel::TRIMESTRE, 1);
        $this->createReferentiel('2e trimestre', CodeReferentiel::TRIMESTRE_2, TypeReferentiel::TRIMESTRE, 2);
        $this->createReferentiel('3e trimestre', CodeReferentiel::TRIMESTRE_3, TypeReferentiel::TRIMESTRE, 3);

        $this->createReferentiel('Sujet type examen', CodeReferentiel::TYPE_EXAMEN, TypeReferentiel::EXAMEN, 1);
        $this->createReferentiel("Sujet d'examen", CodeReferentiel::FINAL_EXAMEN, TypeReferentiel::EXAMEN, 2);

        $this->createReferentiel("Application", CodeReferentiel::APPLICATION, TypeReferentiel::EXERCISE, 1);
        $this->createReferentiel('Synthèse', CodeReferentiel::SYNTHESE, TypeReferentiel::EXERCISE, 2);

        // Etat Justificatif
        $this->createReferentiel('En validation', CodeReferentiel::VALIDATING, TypeReferentiel::ETAT, 1);
        $this->createReferentiel('Validé', CodeReferentiel::VALIDATED, TypeReferentiel::ETAT, 2);
        $this->createReferentiel('Rejecté', CodeReferentiel::REJECTED, TypeReferentiel::ETAT, 3);

        // Type d'enseignement
        $this->createReferentiel('Enseignement', CodeReferentiel::BASIC, TypeReferentiel::ENSEIGNEMENT, 1);
        $this->createReferentiel('FAQ', CodeReferentiel::FAQ, TypeReferentiel::ENSEIGNEMENT, 2);
        $this->createReferentiel('Sujet d\'examen', CodeReferentiel::EXAM_SUBJECT, TypeReferentiel::ENSEIGNEMENT, 3);


        $ref = $this->createReferentiel('En cours', CodeReferentiel::IN_PROGRESS, TypeReferentiel::ETAT_COLLEGE_YEAR, 1);
        $year = date('Y', strtotime(now()));
        CollegeYear::create([
            'name' => ($year - 1) . '-' . $year,
            'year' => $year,
            'etat_id' => $ref->id
        ]);
        $this->createReferentiel('Terminé', CodeReferentiel::FINISHED, TypeReferentiel::ETAT_COLLEGE_YEAR, 2);
    }

    private function createReferentiel($name, $code, $type, $position)
    {
        return Referentiel::create(['name' => $name, 'code' => $code, 'type' => $type, 'position' => $position]);
    }

    private function createCategory($name, $code)
    {
        return Category::create(['name' => $name, 'code' => $code]);
    }

    private function generateClasses()
    {

        $college = Referentiel::where('code', 'college')->where('type', 'level')->first();
        Classe::create($this->generateClasse('Sixième', '6°', 'sixieme', 1, $college));
        Classe::create($this->generateClasse('Cinquième', '5°', 'cinquieme', 2, $college));
        Classe::create($this->generateClasse('Quatrième', '4°', 'quatrieme', 3, $college));
        Classe::create($this->generateClasse('Troisième', '3°', 'troisieme', 4, $college));

        $lycee = Referentiel::where('code', 'lycee')->where('type', 'level')->first();

        Classe::create($this->generateClasse('Seconde A', '2nde A', 'seconde_a', 5, $lycee));
        Classe::create($this->generateClasse('Seconde C', '2nde C', 'seconde_c', 5, $lycee));

        Classe::create($this->generateClasse('Première A', '1ère A', 'premiere_a', 6, $lycee));
        Classe::create($this->generateClasse('Première C', '1ère C', 'premiere_c', 6, $lycee));
        Classe::create($this->generateClasse('Première D', '1ère D', 'premiere_d', 6, $lycee));

        Classe::create($this->generateClasse('Terminale A', 'Tle A', 'terminale_a', 7, $lycee));
        Classe::create($this->generateClasse('Terminale C', 'Tle C', 'terminale_c', 7, $lycee));
        Classe::create($this->generateClasse('Terminale D', 'Tle D', 'terminale_d', 7, $lycee));

        Classe::whereIn('code', ["troisieme", 'terminale_a', 'terminale_c', 'terminale_d'])
            ->update([
                'has_faq' =>  true,
                'is_exam_class' => true
            ]);
    }


    private function generateClasse($name, $abreviation, $code, $niveau, $level)
    {
        return [
            'name' => $name,
            'abreviation' => $abreviation,
            'code' => $code,
            'position' => $niveau,
            'level_id' => $level->id
        ];
    }



    private function generateMatieres()
    {

        $maths = $this->generateMatiere('Mathématiques', 'maths', 'Maths', 1);

        $pc = $this->generateMatiere('Physique-Chimie', 'pc', 'PC', 2);
        $this->generateSpecialite('Physique', 'physique', $pc);
        $this->generateSpecialite('Chimie', 'chimie', $pc);

        $this->generateMatiere('SVT', 'svt', 'SVT', 3);

        $this->generateMatiere('Français', 'francais', 'Fr', 4);

        $this->generateMatiere('Anglais', 'anglais', 'Ang', 5);

        $this->generateMatiere('Allemand', 'allemand', 'All', 6);

        //$histoire = $this->generateMatiere('Histoire','histoire','Hist');

        $hg = $this->generateMatiere('Histoire-Géographie', 'hg', 'HG', 7);
        $this->generateSpecialite('Géographie', 'geographie', $hg);
        $this->generateSpecialite('Histoire', 'histoire', $hg);

        $this->generateMatiere('Philosophie', 'philosophie', 'Phil', 8);
    }

    public function generateSpecialite($name, $code, $matiere)
    {
        $specialite = new Specialite();
        $specialite->name = $name;
        $specialite->code = $code;
        $specialite->matiere_id = $matiere->id;
        $specialite->save();
    }

    private function generateMatiere($name, $code, $abreviation, $position)
    {
        return Matiere::create([
            'name' => $name,
            'code' => $code,
            'abreviation' => $abreviation,
            'position' => $position
        ]);
    }

    private function createTeacher($firstname, $lastname, $email, $matieres, $level)
    {
        $teacher = new Teacher();
        //$teacher->level_id = $level->id;
        $teacher->save();
        $user = new User([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'username' => (new ManageIdentify)->buildIdentify(),
            'email' => $email,
            'email_verified_at' => now(),
            'password' => bcrypt('secret'),
            'avatar' => 'avatar.png',
            'gender_id' => Referentiel::where("code", CodeReferentiel::HOMME)->where("type", TypeReferentiel::GENDER)->first()->id
        ]);
        $teacher->user()->save($user);

        $refEtat = Referentiel::where("code", CodeReferentiel::VALIDATED)->where("type", TypeReferentiel::ETAT)->first();
        collect($matieres)->each(function ($code) use ($teacher, $refEtat, $level) {
            $matiere = Matiere::where('code', $code)->first();
            $teacher->matieres()->attach($matiere->id, [
                'etat_id' => $refEtat->id,
                'level_id' => $level->id
                //'justificatif' => 'test.pdf'
            ]);
        });

        return $teacher;
    }

    private function createStudent($firstname, $lastname, $email, $classe)
    {
        $student = new Student();
        $student->classe_id = $classe;
        $student->save();

        $user = new User([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'username' => (new ManageIdentify)->buildIdentify(),
            'email' => $email,
            'email_verified_at' => now(),
            'password' => bcrypt('secret'),
            'avatar' => 'avatar.png',
            'gender_id' => Referentiel::where("code", CodeReferentiel::HOMME)->where("type", TypeReferentiel::GENDER)->first()->id
        ]);

        $student->user()->save($user);
        return $user;
    }
}
