<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CompagnieController;

//===========Phase Authentification Entite Professionnelle===========

// Routes publiques
Route::post('/login', [CompagnieController::class, 'login']);
Route::post('/Inscription', [CompagnieController::class, 'store']);
Route::get('/type-categories', [CompagnieController::class, 'index']);

// Routes protégées par authentification Sanctum
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/compagnie', [CompagnieController::class, 'getCurrentCompany']);
    Route::post('/logout', [CompagnieController::class, 'logout']);
    Route::put('/profile', [CompagnieController::class, 'updateProfile']);
});
