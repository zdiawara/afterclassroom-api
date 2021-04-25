<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
], function ($router) {
    Route::post('login', 'Api\AuthController@login');
    Route::post('logout', 'Api\AuthController@logout');
    Route::post('refresh', 'Api\AuthController@refresh');
    Route::post('me', 'Api\AuthController@me');
});

Route::apiResource('referentiels', "Api\ReferentielController");

Route::apiResource('teachers', "Api\TeacherController");

Route::group(['prefix' => 'teachers'], function ($router) {
    Route::get('/{teacher}/resume', 'Api\TeacherController@resume')->name('teachers.resume');
    Route::post('/{teacher}/avatar', 'Api\TeacherController@updateAvatar')->name('teachers.updateAvatar');
});

Route::apiResource('teachers.matieres', "Api\TeacherMatiereController");

// Student
Route::apiResource('students', "Api\StudentController");
Route::apiResource('students.teachers', "Api\StudentTeacherController");

// Chapter
Route::apiResource('chapters', "ChapterController");
Route::group(['prefix' => 'chapters'], function ($router) {
    Route::get('/{chapter}/exercises', 'ChapterController@showExercises')->name('chapters.showExercises');
    Route::get('/{chapter}/questions', 'ChapterController@showQuestions')->name('chapters.showQuestions');
});

// Exercise
Route::apiResource('exercises', "Api\ExerciseController");
// Question
Route::apiResource('questions', "Api\QuestionController");
// Book
Route::apiResource('books', "Api\BookController");
// controles
Route::apiResource('controles', "Api\ControleController");

// College year
Route::group(['prefix' => 'college_years'], function ($router) {
    Route::get('/', 'Api\CollegeYearController@index')->name('college_years.index');
});

/////
Route::apiResource('matieres', "Api\MatiereController");
Route::apiResource('matieres.specialites', "Api\SpecialiteController");
Route::apiResource('classes', "Api\ClasseController");

Route::apiResource('files', "Api\FileController");

Route::group(['prefix' => 'files'], function ($router) {
    Route::get('/{root}/{filename}', 'Api\FileController@show')->name('files.show');
});

Route::group(['prefix' => 'mails'], function ($router) {
    Route::post('/', 'Api\MailController@send')->name('mails.send');
});

Route::group(['prefix' => 'events'], function ($router) {
    Route::get('/', 'Api\EventController@readEvents')->name('events.read');
});
