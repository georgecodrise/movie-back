<?php

use App\Http\Controllers\Authcontroller;
use App\Http\Controllers\CarteleraController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\SalaController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::apiResource('/movies',MovieController::class);
//Route::apiResource('/salas',SalaController::class);
//Route::apiResource('/cartelera',CarteleraController::class);

Route::apiResources([
    'movies'=>MovieController::class,
    'salas'=>SalaController::class,
    'cartelera'=>CarteleraController::class,
    'reservas'=>ReservaController::class,

]);



Route::get('/movies-all',[MovieController::class,'listar']);

Route::patch('/cartelera/edit/{id}',[CarteleraController::class,'estado']);

Route::post('/register',[Authcontroller::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/logout',[AuthController::class,'logout']);