<?php

use App\Http\Controllers\Api\LoginController;
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
    Route::post('/users', [UserController::class, 'update'])->name('users.update');
});

Route::post('/login', LoginController::class)->name('users.login');
