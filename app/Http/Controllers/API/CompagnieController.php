<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Compagnie;
use App\Models\TypeCategorie;
use App\Mail\CompteCompagnieCree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class CompagnieController extends Controller
{
    /**
     * Formater la réponse JSON pour une compagnie.
     */
    protected function formatCompanyResponse(Compagnie $compagnie, array $additional = [])
    {
        return array_merge([
            'compagnie' => [
                'id' => $compagnie->id,
                'nom' => $compagnie->nom,
                'CMPID' => $compagnie->CMPID,
                'email' => $compagnie->email,
                'logo' => $compagnie->logo,
                'description' => $compagnie->description,
                'telephone' => $compagnie->telephone,
                'adresse' => $compagnie->adresse,
                'site_web' => $compagnie->site_web,
                'profil_complet' => $compagnie->profil_complet,
                'categorie' => $compagnie->typeCategorie ? [
                    'id' => $compagnie->typeCategorie->id,
                    'nom' => $compagnie->typeCategorie->nom,
                ] : null,
            ],
        ], $additional);
    }

    /**
     * Créer une nouvelle compagnie et envoyer les accès par email.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:compagnies,email',
            'type_categorie_id' => 'required|exists:type_categories,id',
        ]);

        $cmpid = strtoupper('CMP' . Str::random(8));
        $motDePasse = Str::random(10);

        $compagnie = Compagnie::create([
            'nom' => $validated['nom'],
            'email' => $validated['email'],
            'CMPID' => $cmpid,
            'mot_de_passe' => Hash::make($motDePasse),
            'type_categorie_id' => $validated['type_categorie_id'],
            'profil_complet' => false,
        ]);

        $compagnie->load('typeCategorie');

        try {
            Mail::to($compagnie->email)->send(new CompteCompagnieCree($compagnie, $motDePasse));
        } catch (\Exception $e) {
            // Erreur dans l'envoi de l'email, mais on retourne quand même la réponse
            Log::error('Erreur envoi email compagnie : ' . $e->getMessage());
            return response()->json([
                'message' => 'Compte créé, mais une erreur est survenue lors de l\'envoi de l\'email.',
                'details' => $this->formatCompanyResponse($compagnie),
            ], 201);
        }

        return response()->json(array_merge(
            $this->formatCompanyResponse($compagnie),
            ['message' => 'Compte compagnie créé avec succès', 'CMPID' => $cmpid]
        ), 201);
    }


    /**
     * Retourne toutes les catégories disponibles.
     */
    public function index()
    {
        return response()->json(TypeCategorie::select('id', 'nom')->get());
    }

    /**
     * Connexion d'une compagnie.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'CMPID' => 'required|string|exists:compagnies,CMPID',
            'mot_de_passe' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $compagnie = Compagnie::where('CMPID', $request->CMPID)->first();

        if ($compagnie && Hash::check($request->mot_de_passe, $compagnie->mot_de_passe)) {
            $token = $compagnie->createToken('auth_token')->plainTextToken;

            $compagnie->load('typeCategorie');

            return response()->json(array_merge(
                $this->formatCompanyResponse($compagnie),
                ['token' => $token]
            ), 200);
        }

        return response()->json(['message' => 'Identifiants incorrects.'], 401);
    }

    /**
     * Récupérer les données de la compagnie connectée.
     */
    public function getCurrentCompany(Request $request)
    {
        $compagnie = $request->user()->load('typeCategorie');
        return response()->json($this->formatCompanyResponse($compagnie), 200);
    }

    /**
     * Déconnexion de la compagnie.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Déconnexion réussie'], 200);
    }

    /**
     * Mettre à jour le profil de la compagnie.
     */
    public function updateProfile(Request $request)
    {
        $compagnie = $request->user();
        $validated = $request->validate([
            'nom' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:compagnies,email,' . $compagnie->id,
            'type_categorie_id' => 'sometimes|exists:type_categories,id',
            'logo' => 'nullable|string',
            'description' => 'nullable|string',
            'telephone' => 'nullable|string',
            'adresse' => 'nullable|string',
            'site_web' => 'nullable|string',
            'profil_complet' => 'sometimes|boolean',
        ]);

        $compagnie->update($validated);
        $compagnie->load('typeCategorie');

        return response()->json(array_merge(
            $this->formatCompanyResponse($compagnie),
            ['message' => 'Profil mis à jour avec succès']
        ), 200);
    }
}
