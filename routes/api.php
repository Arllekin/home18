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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/login/{email?}/{remember_token?}', [\App\Http\Controllers\Api\UserController::class, 'login'])
    ->name('users.login');
Route::post('/addUser', [\App\Http\Controllers\Api\UserController::class, 'store'])
    ->name('users.store');
Route::get('/users/list/{names?}/{emails?}/{verify?}/{countries?}', [\App\Http\Controllers\Api\UserController::class, 'list'])
    ->name('users.list');
Route::post('/users/edit', [\App\Http\Controllers\Api\UserController::class, 'edit'])
    ->name('users.edit');
Route::delete('/users/destroy', [\App\Http\Controllers\Api\UserController::class, 'destroy'])
    ->name('users.destroy')
    ->middleware('auth:sanctum');
;


Route::post('/projects/add', [\App\Http\Controllers\Api\ProjectController::class, 'store'])
    ->name('projects.store')
    ->middleware('auth:sanctum');
Route::post('/projects/link', [\App\Http\Controllers\Api\ProjectController::class, 'link'])
    ->name('projects.link')
    ->middleware('auth:sanctum');
Route::get('/projects/list/{emails?}/{continents?}/{labels?}', [\App\Http\Controllers\Api\ProjectController::class, 'list'])
    ->name('projects.list')
    ->middleware('auth:sanctum');
Route::delete('/projects/destroy', [\App\Http\Controllers\Api\ProjectController::class, 'destroy'])
    ->name('projects.destroy')
    ->middleware('auth:sanctum');
;



Route::post('/labels/store', [\App\Http\Controllers\Api\LabelController::class, 'store'])
    ->name('labels.store')
    ->middleware('auth:sanctum');
Route::post('/labels/link', [\App\Http\Controllers\Api\LabelController::class, 'link'])
    ->name('labels.link')
    ->middleware('auth:sanctum');
Route::get('/labels/list/{emails?}/{projects?}', [\App\Http\Controllers\Api\LabelController::class, 'list'])
    ->name('labels.list')
    ->middleware('auth:sanctum');
Route::delete('/labels/destroy', [\App\Http\Controllers\Api\LabelController::class, 'destroy'])
    ->name('labels.destroy')
    ->middleware('auth:sanctum');
;


