<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CompagnieController;
use App\Http\Controllers\API\Vetement\CommandeVetementController;
use App\Http\Controllers\API\ProduitVetementController;
use App\Http\Controllers\API\UtilisateurConnecterController;

//===========Phase Authentification Entite Professionnelle===========

// Routes publiques
Route::post('/login', [CompagnieController::class, 'login']);
Route::post('/Inscription', [CompagnieController::class, 'store']);
Route::get('/type-categories', [CompagnieController::class, 'index']);
Route::middleware('auth:sanctum')->get('/utilisateur-connecte', [UtilisateurConnecterController::class, 'me']);




// Routes protégées par authentification Sanctum
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/compagnie', [CompagnieController::class, 'getCurrentCompany']);
    Route::post('/logout', [CompagnieController::class, 'logout']);
    Route::put('/profile', [CompagnieController::class, 'updateProfile']);
});

//======== Passé Commande Vetement ==========

Route::prefix('vetement')->group(function () {
    Route::post('commandes', [CommandeVetementController::class, 'store']);
});

//===========Phase De Gestion de Vetement===========

Route::get('/vetement/categories', [ProduitVetementController::class, 'getCategories']);
Route::get('/vetement/sous-categories/{id}', [ProduitVetementController::class, 'getSousCategories']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('vetement-produits', [ProduitVetementController::class, 'index']);
    Route::post('vetement-produits', [ProduitVetementController::class, 'store']);
    Route::get('vetement-produits/{id}', [ProduitVetementController::class, 'show']);
    Route::put('vetement-produits/{id}', [ProduitVetementController::class, 'update']);
    Route::delete('vetement-produits/{id}', [ProduitVetementController::class, 'destroy']);
});
