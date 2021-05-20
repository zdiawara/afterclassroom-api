<?php

namespace App;

class Generate
{

    public static function getTitre($faker, $tag)
    {
        return '<' . $tag . '>' . $faker->text(rand(10, 30)) . '</' . $tag . '>';
    }

    public static function getParagraphe($faker, $n)
    {
        return '<p>' . $faker->paragraph($n, true) . '</p>';
    }

    public static function genContent($faker)
    {

        $content = '';
        // Titre 1
        $content = $content . Generate::getTitre($faker, 'h1');
        if ($faker->numberBetween(1, 2) % 2 == 0) {
            $content = $content . Generate::getParagraphe($faker, 3);
        }
        // Titre 2
        $content = $content . Generate::getTitre($faker, 'h2') . Generate::getParagraphe($faker, 4);

        // Titre 3
        $content = $content . Generate::getTitre($faker, 'h3') . Generate::getParagraphe($faker, 4) . Generate::getParagraphe($faker, 1);
        $content = $content . Generate::getTitre($faker, 'h3') . Generate::getParagraphe($faker, 4) . Generate::getParagraphe($faker, 2);
        $content = $content . Generate::getTitre($faker, 'h3') . Generate::getParagraphe($faker, 4) . Generate::getParagraphe($faker, 1);

        // Titre 2
        $content = $content . Generate::getTitre($faker, 'h2') . Generate::getParagraphe($faker, 4);

        // Titre 2
        $content = $content . Generate::getTitre($faker, 'h2') . Generate::getParagraphe($faker, 2);
        $content = $content . Generate::getTitre($faker, 'h3') . Generate::getParagraphe($faker, 1) . Generate::getParagraphe($faker, 3);

        // Titre 1
        $content = $content . Generate::getTitre($faker, 'h1');
        if ($faker->numberBetween(1, 2) % 2 == 0) {
            $content = $content . Generate::getParagraphe($faker, 3);
        }

        // Titre 1
        $content = $content . Generate::getTitre($faker, 'h1');
        if ($faker->numberBetween(1, 2) % 2 == 0) {
            $content = $content . Generate::getParagraphe($faker, 3);
        }

        return $content;
    }

    public static function genApplication($faker)
    {
        return '<div class="question-application">' .
            '<div class="question-application--enonce">'
            . $faker->paragraph(1, true) .
            '</div>' .
            '<div class="question-application--response">'
            . $faker->paragraph(1, true) .
            '</div>' .
            '</div>';
    }

    public static function genQuestion($faker)
    {

        $content = '<div class="question-todo">'
            . $faker->paragraph(1, true) .
            '</div>' .
            '<div class="question-toknow">'
            . $faker->paragraph(1, true) .
            '</div>';

        $nbApplication = rand(1, 3);
        for ($i = 1; $i <= $nbApplication; $i++) {
            $content = $content . self::genApplication($faker);
        }
        return $content;
    }

    public static function genExoContent($faker)
    {

        $content = '';
        if ($faker->numberBetween(1, 5) % 2 == 0) {
            $content = $content . '<p>' . $faker->paragraph(3, true) . "</p>";
        }

        $nbQuestion = rand(3, 6);
        $content =  $content . '<ol>';
        for ($i = 1; $i < $nbQuestion; $i++) {
            $content = $content . '<li>' . $faker->paragraph(rand(1, 3), true) . "</li>";
            $nbSubQuestion = rand(0, 2);
            if ($nbSubQuestion > 0) {
                $content = $content . '<ol type="a">';
            }
            for ($j = 0; $j < $nbSubQuestion; $j++) {
                $content = $content . "<li>" . $faker->paragraph(rand(1, 3), true) . "</li>";
            }
            if ($nbSubQuestion > 0) {
                $content = $content . '</ol>';
            }
        }
        $content =  $content . '</ol>';

        return $content;
    }
}
