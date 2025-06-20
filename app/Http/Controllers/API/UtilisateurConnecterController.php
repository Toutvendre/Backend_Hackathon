<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UtilisateurConnecterController extends Controller
{
    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'compagnie' => $user, // ou $user->compagnie selon votre structure
            'success' => true
        ]);
    }
}
