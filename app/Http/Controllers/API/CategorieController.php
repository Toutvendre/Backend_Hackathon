<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TypeCategorie;
use App\Models\VetementCategory;
use App\Models\VetementSousCategorie;

class CategorieController extends Controller
{
    // Récupère toutes les catégories métier (type categories)
    public function getTypeCategories()
    {
        return response()->json(TypeCategorie::all());
    }

    // Récupère les catégories vêtement liées à la catégorie métier "Vêtement"
    public function getVetementCategories()
    {
        // Trouver la catégorie métier "Vêtement"
        $typeVetement = TypeCategorie::where('nom', 'Vêtement')->first();

        if (!$typeVetement) {
            return response()->json([], 404);
        }

        // Récupérer les catégories vêtements associées
        $categories = VetementCategory::where('type_categorie_id', $typeVetement->id)->get();

        return response()->json($categories);
    }

    // Récupère les sous-catégories d'une catégorie vêtement donnée
    public function getVetementSousCategories($vetementCategorieId)
    {
        $sousCategories = VetementSousCategorie::where('vetement_categorie_id', $vetementCategorieId)->get();

        return response()->json($sousCategories);
    }
}
