<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\LogoutController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
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


Route::middleware('auth:sanctum')->group(function () {
    //Route::apiResource('/users', UserController::class)->only(['index']);
    Route::get('/users', [UserController::class, 'index'])->name('users.read');
    Route::put('/users', [UserController::class, 'update'])->name('users.update');


    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.list');
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.read');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'delete'])->name('tasks.delete');

    Route::post('/logout', LogoutController::class)->name('users.logout');
});

Route::post('/login', LoginController::class)->name('users.login');
Route::post('/register', RegisterController::class)->name('users.register');
