<?php

namespace App\Http\Controllers\API\Vetement;

use App\Http\Controllers\Controller;
use App\Models\CommandeVetement;
use App\Models\ProduitVetement;
use Illuminate\Http\Request;

class CommandeVetementController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'vetement_produit_id' => 'required|exists:vetement_produits,id',
            'client_nom' => 'required|string',
            'client_telephone' => 'required|string',
            'quantite' => 'required|integer|min:1',
        ]);

        $produit = ProduitVetement::findOrFail($request->vetement_produit_id);

        // Vérifie le stock
        if ($produit->stock < $request->quantite) {
            return response()->json(['message' => 'Stock insuffisant'], 422);
        }

        // Déduction du stock
        $produit->stock -= $request->quantite;
        if ($produit->stock <= 0) {
            $produit->disponible = false;
        }
        $produit->save();

        // Création de la commande
        $commande = CommandeVetement::create([
            'vetement_produit_id' => $produit->id,
            'compagnie_id' => $produit->compagnie_id,
            'client_nom' => $request->client_nom,
            'client_telephone' => $request->client_telephone,
            'quantite' => $request->quantite,
            'prix_total' => $produit->prix * $request->quantite,
        ]);

        return response()->json([
            'message' => 'Commande enregistrée avec succès',
            'commande' => $commande,
        ], 201);
    }
}
