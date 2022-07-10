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

Route::post("/stat", [\App\Http\Controllers\StatsController::class, "create"]);
Route::get("/stat", [\App\Http\Controllers\StatsController::class, "all"]);
Route::get("/stat/temp", [\App\Http\Controllers\StatsController::class, "allTemp"]);

Route::post("/stat/cached", [\App\Http\Controllers\StatsController::class, "createCache"]);
Route::get("/stat/cached", [\App\Http\Controllers\StatsController::class, "getAllCached"]);
Route::get("/stat/cached/{player_id}", [\App\Http\Controllers\StatsController::class, "getCached"]);
