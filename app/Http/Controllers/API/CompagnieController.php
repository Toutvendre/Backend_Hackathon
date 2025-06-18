<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Compagnie;
use Illuminate\Http\Request;

class CompagnieController extends Controller
{
    public function index()
    {
        return response()->json(Compagnie::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        $compagnie = Compagnie::create($validated);

        return response()->json($compagnie, 201);
    }

    public function show(Compagnie $compagnie)
    {
        return response()->json($compagnie);
    }

    public function update(Request $request, Compagnie $compagnie)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        $compagnie->update($validated);

        return response()->json($compagnie);
    }

    public function destroy(Compagnie $compagnie)
    {
        $compagnie->delete();

        return response()->noContent();
    }
}
