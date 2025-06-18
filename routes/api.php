<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\VoyageController;
use App\Http\Controllers\API\CompagnieController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/test', function() {
    return response()->json(['message' => 'API fonctionne']);
});

// Routes publiques pour les voyages (index, show, store, update, destroy)
Route::apiResource('voyages', VoyageController::class);



Route::apiResource('compagnies', CompagnieController::class);
