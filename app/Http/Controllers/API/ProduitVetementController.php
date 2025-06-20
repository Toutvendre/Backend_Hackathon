<?php

namespace App\Http\Controllers\API;

use App\Models\ProduitVetement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProduitVetementController extends Controller
{
    public function index(Request $request)
    {
        $query = ProduitVetement::with(['sousCategorie.categorie', 'compagnie']);

        if ($request->filled('categorie_id')) {
            $query->where('vetement_categorie_id', $request->categorie_id);
        }

        if ($request->filled('sous_categorie_id')) {
            $query->where('vetement_sous_categorie_id', $request->sous_categorie_id);
        }

        if ($request->filled('compagnie_id')) {
            $query->where('compagnie_id', $request->compagnie_id);
        }

        if ($request->boolean('disponible_only')) {
            $query->where('stock', '>', 0);
        }

        return response()->json($query->get());
    }

    // NOUVELLE méthode pour récupérer les produits d'une sous-catégorie
    public function getProduitsParSousCategorie($id)
    {
        $produits = ProduitVetement::with(['sousCategorie.categorie', 'compagnie'])
            ->where('vetement_sous_categorie_id', $id)
            ->where('stock', '>', 0) // optionnel: uniquement disponibles
            ->get();

        return response()->json($produits);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prix' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'vetement_categorie_id' => 'required|exists:vetement_categories,id',
            'vetement_sous_categorie_id' => 'required|exists:vetement_sous_categories,id',
            'compagnie_id' => 'required|exists:compagnies,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        if ($request->hasFile('image')) {
            try {
                $path = $request->file('image')->store('produits/vetements', 'public');
                $data['image'] = Storage::url($path);
            } catch (\Exception $e) {
                Log::error('Erreur upload image: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'upload de l\'image'
                ], 500);
            }
        }

        $produit = ProduitVetement::create($data);

        return response()->json([
            'success' => true,
            'data' => $produit->load(['sousCategorie.categorie', 'compagnie']),
            'message' => 'Produit créé avec succès'
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $produit = ProduitVetement::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nom' => 'sometimes|string|max:255',
            'prix' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'vetement_sous_categorie_id' => 'sometimes|exists:vetement_sous_categories,id',
            'compagnie_id' => 'sometimes|exists:compagnies,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        if ($request->hasFile('image')) {
            if ($produit->image) {
                $oldImagePath = str_replace('/storage/', '', $produit->image);
                Storage::disk('public')->delete($oldImagePath);
            }

            $path = $request->file('image')->store('produits/vetements', 'public');
            $data['image'] = Storage::url($path);
        }

        $produit->update($data);

        return response()->json([
            'success' => true,
            'data' => $produit->load(['sousCategorie.categorie', 'compagnie']),
            'message' => 'Produit mis à jour avec succès'
        ]);
    }

    public function destroy($id)
    {
        $produit = ProduitVetement::findOrFail($id);

        if ($produit->image) {
            $imagePath = str_replace('/storage/', '', $produit->image);
            Storage::disk('public')->delete($imagePath);
        }

        $produit->delete();

        return response()->json([
            'success' => true,
            'message' => 'Produit supprimé avec succès'
        ]);
    }
}
