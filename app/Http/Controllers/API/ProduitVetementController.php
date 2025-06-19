<?php

namespace App\Http\Controllers\API;

use App\Models\ProduitVetement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\VetementCategory;
use App\Models\VetementSousCategorie;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProduitVetementController extends Controller
{
    public function index()
    {
        return ProduitVetement::with(['sousCategorie.categorie', 'compagnie'])->get();
    }

    public function store(Request $request)
    {
        Log::info('Requête store reçue:', $request->all()); // Log pour débogage

        $validator = Validator::make($request->all(), [
            'nom' => 'required|string',
            'prix' => 'required|numeric',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:5120',
            'vetement_categorie_id' => 'required|exists:vetement_categories,id',
            'vetement_sous_categorie_id' => 'required|exists:vetement_sous_categories,id',
            'compagnie_id' => 'required|exists:compagnies,id',
        ]);



        if ($validator->fails()) {
            Log::error('Erreur de validation:', $validator->errors()->toArray());
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('produits', 'public');
            $data['image'] = Storage::url($path);
        }


        $produit = ProduitVetement::create($data);
        Log::info('Produit créé:', $produit->toArray());

        return response()->json($produit, 201);
    }

    public function getCategories()
    {
        $categories = VetementCategory::select('id', 'nom')->get();
        return response()->json($categories);
    }

    public function getSousCategories($categorieId)
    {
        $sousCategories = VetementSousCategorie::where('vetement_categorie_id', $categorieId)
            ->select('id', 'nom')
            ->get();
        return response()->json($sousCategories);
    }

    public function show($id)
    {
        $produit = ProduitVetement::with(['sousCategorie.categorie', 'compagnie'])->findOrFail($id);
        return response()->json($produit);
    }

    public function update(Request $request, $id)
    {
        $produit = ProduitVetement::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nom' => 'sometimes|string',
            'prix' => 'sometimes|numeric',
            'stock' => 'sometimes|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'vetement_sous_categorie_id' => 'sometimes|exists:vetement_sous_categories,id',
            'compagnie_id' => 'sometimes|exists:compagnies,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $produit->update($validator->validated());
        return response()->json($produit);
    }

    public function destroy($id)
    {
        $produit = ProduitVetement::findOrFail($id);
        $produit->delete();
        return response()->json(['message' => 'Produit supprimé']);
    }
}
