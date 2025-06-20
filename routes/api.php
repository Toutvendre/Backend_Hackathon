<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CompagnieController;
use App\Http\Controllers\API\ProduitVetementController;
use App\Http\Controllers\API\UtilisateurConnecterController;
use App\Http\Controllers\API\PlatController;
use App\Http\Controllers\API\CategorieController;
use App\Http\Controllers\API\CommandeVetementController;


//===========Phase Authentification Entite Professionnelle===========

// Routes publiques
Route::post('/login', [CompagnieController::class, 'login']);
Route::post('/Inscription', [CompagnieController::class, 'store']);
Route::get('/type-categories', [CompagnieController::class, 'index']);

// Routes protégées par authentification Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/utilisateur-connecte', [UtilisateurConnecterController::class, 'me']);
    Route::get('/compagnie', [CompagnieController::class, 'getCurrentCompany']);
    Route::post('/logout', [CompagnieController::class, 'logout']);
    Route::put('/profile', [CompagnieController::class, 'updateProfile']);
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

//===========Phase De Gestion des Plats===========

// Routes publiques pour les catégories de plats
Route::get('/plats/categories', [PlatController::class, 'getCategories']);

// Routes protégées pour la gestion des plats
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/plats', [PlatController::class, 'index']);
    Route::post('/plats', [PlatController::class, 'store']);
    Route::get('/plats/{id}', [PlatController::class, 'show']);
    Route::put('/plats/{id}', [PlatController::class, 'update']);
    Route::delete('/plats/{id}', [PlatController::class, 'destroy']);
});



Route::prefix('categories')->group(function () {
    Route::get('/type', [CategorieController::class, 'getTypeCategories']);
    Route::get('/vetement', [CategorieController::class, 'getVetementCategories']);
    Route::get('/vetement/{id}/sous-categories', [CategorieController::class, 'getVetementSousCategories']);
});

Route::prefix('vetement-sous-categories')->group(function () {
    Route::get('{id}/produits', [ProduitVetementController::class, 'getProduitsParSousCategorie']);
});




Route::prefix('commandes')->group(function () {
    Route::post('/vetement', [CommandeVetementController::class, 'store']);
    Route::post('/vetement/transaction/{transaction_id}/verifier-otp', [CommandeVetementController::class, 'verifierOtp']);
});
