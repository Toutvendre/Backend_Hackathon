<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProduitVetement;
use App\Models\CommandeVetement;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Support\Facades\Storage;

class CommandeVetementController extends Controller
{
    public function store(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'produit_id' => 'required|exists:produit_vetements,id',
            'compagnie_id' => 'required|exists:compagnies,id',
            'quantite' => 'required|integer|min:1',
            'client_nom' => 'required|string|max:255',
            'client_telephone' => 'required|string|max:20',
            'livraison' => 'required|boolean',
            'adresse_livraison' => 'required_if:livraison,1|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Récupérer le produit
        $produit = ProduitVetement::findOrFail($request->produit_id);

        // Vérifier le stock
        if ($produit->stock < $request->quantite) {
            return response()->json(['message' => 'Stock insuffisant.'], 400);
        }

        // Vérifier que compagnie_id correspond au produit
        if ($produit->compagnie_id !== $request->compagnie_id) {
            return response()->json(['message' => 'Compagnie non valide pour ce produit.'], 400);
        }

        // Calculer le prix total
        $prix_total = $produit->prix * $request->quantite;

        // Créer la commande
        $commande = CommandeVetement::create([
            'vetement_produit_id' => $produit->id,
            'compagnie_id' => $request->compagnie_id,
            'client_nom' => $request->client_nom,
            'client_telephone' => $request->client_telephone,
            'livraison' => $request->livraison,
            'adresse_livraison' => $request->livraison ? $request->adresse_livraison : null,
            'notes' => $request->notes,
            'quantite' => $request->quantite,
            'prix_total' => $prix_total,
            'statut' => 'en_attente',
            'date_commande' => now(),
        ]);

        // Générer OTP et code de transaction
        $otp = rand(100000, 999999);
        $code_transaction = 'TX-' . strtoupper(Str::random(8));

        // Créer la transaction simulée
        $transaction = Transaction::create([
            'commande_vetement_id' => $commande->id,
            'code_transaction' => $code_transaction,
            'telephone' => $request->client_telephone,
            'montant' => $prix_total,
            'otp' => $otp,
            'statut' => 'en_attente',
        ]);

        return response()->json([
            'message' => 'Commande créée. Entrez l\'OTP envoyé sur votre numéro Orange Money pour valider le paiement.',
            'commande' => $commande->load(['produit', 'compagnie']),
            'transaction' => $transaction,
            'otp' => $otp,
        ], 201);
    }

    public function verifierOtp(Request $request, $transaction_id)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $transaction = Transaction::findOrFail($transaction_id);

        if ($transaction->statut === 'effectuee') {
            $commande = $transaction->commande()->with(['produit', 'compagnie', 'transaction'])->first();
            return response()->json([
                'message' => 'Paiement déjà validé.',
                'commande' => $commande,
            ], 200);
        }

        if ($transaction->otp === $request->otp) {
            $transaction->update([
                'statut' => 'effectuee',
                'verifie_a' => now(),
            ]);

            $commande = $transaction->commande;
            $produit = $commande->produit;

            $produit->stock -= $commande->quantite;
            $produit->save();

            $commande->statut = 'en_cours';

            // 💡 Génération automatique du numéro de reçu si manquant
            if (!$commande->numero_recu) {
                $dernierRecepisse = CommandeVetement::whereNotNull('numero_recu')
                    ->orderBy('numero_recu', 'desc')
                    ->pluck('numero_recu')
                    ->first();

                $dernierNumero = $dernierRecepisse ? intval(preg_replace('/\D/', '', $dernierRecepisse)) : 0;
                $commande->numero_recu = 'N°' . str_pad($dernierNumero + 1, 7, '0', STR_PAD_LEFT);
            }

            $commande->save();

            $commande->load(['produit', 'compagnie', 'transaction']);

            return response()->json([
                'message' => 'Paiement validé.',
                'commande' => $commande,
            ], 200);
        }

        return response()->json(['error' => 'OTP incorrect.'], 401);
    }
}
