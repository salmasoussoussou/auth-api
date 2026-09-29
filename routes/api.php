<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

// Routes publiques (sans token)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1');   // 5 tentatives maximum par minute

// Route refresh : accepte SEULEMENT le refresh token
Route::post('/refresh', [AuthController::class, 'refresh'])
    ->middleware(['auth:sanctum', 'abilities:issue-access-token']);

// Routes protégées : acceptent SEULEMENT l'access token
Route::middleware(['auth:sanctum', 'abilities:access-api'])->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/logout-all', [AuthController::class, 'logoutAll']);
});