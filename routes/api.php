<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookApiController;
use App\Http\Controllers\Api\SpaClientController;

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

Route::prefix('books')->group(function () {
    Route::get('/', [BookApiController::class, 'index']);
    Route::get('/top', [BookApiController::class, 'topBooks']);
    Route::get('/{id}', [BookApiController::class, 'show']);
    Route::post('/{id}/purchase', [BookApiController::class, 'purchase']);
});

// Маршруты для SPA клиентов
Route::prefix('spa-clients')->group(function () {
    Route::get('/', [SpaClientController::class, 'index']);
    Route::post('/', [SpaClientController::class, 'store']);
    Route::get('/{id}', [SpaClientController::class, 'show']);
    Route::put('/{id}', [SpaClientController::class, 'update']);
});
