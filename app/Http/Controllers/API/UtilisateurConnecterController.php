<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UtilisateurConnecterController extends Controller
{
    public function me(Request $request)
    {
        $compagnie = $request->user();
        return response()->json($compagnie);
    }
}
