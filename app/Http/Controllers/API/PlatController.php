<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CategoriePlat;
use App\Models\Plat;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class PlatController extends Controller
{
    public function index()
    {
        $plats = Plat::with('categorie', 'compagnie')->get();
        return response()->json($plats);
    }



    public function store(Request $request)
    {
        Log::info('Requête store reçue:', $request->all());

        $validator = Validator::make($request->all(), [
            'compagnie_id' => 'required|exists:compagnies,id',
            'categorie_plat_id' => 'required|exists:categorie_plats,id',
            'nom' => 'required|string|max:255',
            'prix' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:5120',
            'disponibilite' => 'nullable|boolean',
            'temps_preparation' => 'nullable|string',
            'ingredients' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            Log::error('Erreur de validation:', $validator->errors()->toArray());
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('plats', 'public');
            $data['image'] = Storage::url($path);
        }

        $plat = Plat::create($data);

        Log::info('Plat créé:', $plat->toArray());

        return response()->json($plat, 201);
    }
    public function getCategories()
    {
        $categories = CategoriePlat::all();
        return response()->json($categories);
    }

    public function show(Plat $plat)
    {
        $plat->load('categorie', 'compagnie');
        return response()->json($plat);
    }



    public function update(Request $request, Plat $plat)
    {
        $validated = $request->validate([
            'compagnie_id' => 'sometimes|exists:compagnies,id',
            'categorie_plat_id' => 'sometimes|exists:categorie_plats,id',
            'nom' => 'sometimes|string|max:255',
            'prix' => 'sometimes|numeric|min:0',
            'image' => 'nullable|string',
            'stock' => 'nullable|integer|min:0',
            'disponibilite' => 'nullable|boolean',
            'temps_preparation' => 'nullable|string',
            'ingredients' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $plat->update($validated);

        return response()->json($plat);
    }

    public function destroy(Plat $plat)
    {
        $plat->delete();
        return response()->json(['message' => 'Plat supprimé avec succès.']);
    }
}
