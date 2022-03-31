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

Route::group(['prefix' => 'teachers'], function () {
    Route::get('/{teacher}/writers', 'Api\TeacherController@writers')->name('teacher.writers');
});

Route::apiResource('writers', "Api\WriterController");

Route::group(['prefix' => 'writers'], function ($router) {
    Route::get('/{writer}/teachers', 'Api\WriterController@teachers')->name('writer.teachers');
});

Route::group(['prefix' => 'teachers'], function ($router) {
    Route::get('/{teacher}/resume', 'Api\TeacherController@resume')->name('teachers.resume');
    Route::post('/{teacher}/avatar', 'Api\TeacherController@updateAvatar')->name('teachers.updateAvatar');
});

Route::apiResource('teachers.matieres', "Api\TeacherMatiereController");
Route::apiResource('teachers.writers', "Api\TeacherWriterController");

// Student
Route::apiResource('students', "Api\StudentController");
Route::apiResource('students.classes', "Api\StudentClasseController");

// Subscription
Route::apiResource('subscriptions', "Api\SubscriptionController");

// Chapter
Route::apiResource('chapters', "Api\ChapterController");
Route::group(['prefix' => 'chapters'], function () {
    Route::get('/{chapter}/exercises', 'Api\ChapterController@showExercises')->name('chapters.showExercises');
    Route::get('/{chapter}/questions', 'Api\ChapterController@showQuestions')->name('chapters.showQuestions');
    Route::post('/positions', 'Api\ChapterController@updatePositions')->name('chapters.updatePositions');
});

Route::apiResource('notions', "Api\NotionController");
Route::group(['prefix' => 'notions'], function () {
    Route::get('/{notion}/questions', 'Api\NotionController@showQuestions')->name('notions.showQuestions');
});

// Exercise
Route::apiResource('exercises', "Api\ExerciseController");
Route::group(['prefix' => 'exercises'], function () {
    Route::post('/positions', 'Api\ExerciseController@updatePositions')->name('exercises.updatePositions');
});

// Question
Route::apiResource('questions', "Api\QuestionController");
Route::group(['prefix' => 'questions'], function () {
    Route::post('/positions', 'Api\QuestionController@updatePositions')->name('questions.updatePositions');
});

// Book
Route::apiResource('books', "Api\BookController");
// controles
Route::apiResource('controles', "Api\ControleController");
Route::group(['prefix' => 'controles'], function () {
    Route::post('/positions', 'Api\ControleController@updatePositions')->name('controles.updatePositions');
});

// College year
Route::group(['prefix' => 'college_years'], function ($router) {
    Route::get('/', 'Api\CollegeYearController@index')->name('college_years.index');
    Route::post('/', 'Api\CollegeYearController@store')->name('college_years.store');
});

/////
Route::apiResource('matieres', "Api\MatiereController");
Route::apiResource('matieres.classes', "Api\ClasseMatiereController");
Route::apiResource('classes-matieres', "Api\ClasseMatiereController");
Route::apiResource('classes', "Api\ClasseController");
Route::group(['prefix' => 'classes'], function ($router) {
    Route::get('/{classe}/matieres', 'Api\ClasseController@showMatieres')->name('classe.matieres');
});

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
