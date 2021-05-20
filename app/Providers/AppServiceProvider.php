<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Relation::morphMap([
            'book' => 'App\Book',
            'exercise' => 'App\Exercise',
            'solution' => 'App\Solution',
            'devoir' => 'App\Devoir',
            'examen' => 'App\Examen',
            'student' => 'App\Student',
            'teacher' => 'App\Teacher',
            'admin' => 'App\Admin',
            'chapter' => 'App\Chapter',
            'bookclasse' => 'App\BookClasse'
        ]);
    }
}
