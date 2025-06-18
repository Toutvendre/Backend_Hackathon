<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Voyage;
use Illuminate\Http\Request;

class VoyageController extends Controller
{
    public function index()
    {
        return response()->json(Voyage::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string',
            'type_transport' => 'required|string',
            'type_trajet' => 'required|string',
            'ville_depart' => 'required|string',
            'ville_arrivee' => 'required|string',
            'heure_depart' => 'required|date_format:H:i',
            'heure_arrivee' => 'required|date_format:H:i',
            'prix' => 'required|numeric',
            'localisation' => 'nullable|string',
           'compagnie_id' => 'nullable|exists:compagnies,id',

        ]);

        $voyage = Voyage::create($validated);

        return response()->json($voyage, 201);
    }

    public function show(Voyage $voyage)
    {
        return response()->json($voyage);
    }

    public function update(Request $request, Voyage $voyage)
    {
        $validated = $request->validate([
            'nom' => 'sometimes|required|string',
            'type_transport' => 'sometimes|required|string',
            'type_trajet' => 'sometimes|required|string',
            'ville_depart' => 'sometimes|required|string',
            'ville_arrivee' => 'sometimes|required|string',
            'heure_depart' => 'sometimes|required|date_format:H:i',
            'heure_arrivee' => 'sometimes|required|date_format:H:i',
            'prix' => 'sometimes|required|numeric',
            'localisation' => 'nullable|string',
            'compagnie_id' => 'nullable|exists:compagnies,id',

        ]);

        $voyage->update($validated);

        return response()->json($voyage);
    }

    public function destroy(Voyage $voyage)
    {
        $voyage->delete();
        return response()->noContent();
    }
}
