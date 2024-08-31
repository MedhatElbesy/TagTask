<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/verify', [VerificationController::class, 'verify']);
Route::post('/logout',[LogoutController::class,'logout'])->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('tags', TagController::class);
    Route::apiResource('posts', PostController::class);

    Route::controller(PostController::class)->group(function (){

        Route::get('deleted-posts','viewDeleted');
        Route::get('restore-post/{id}','restore');
    });

    Route::get('/stats', [StatsController::class, 'stats']);
});
