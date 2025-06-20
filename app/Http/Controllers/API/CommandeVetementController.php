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
            'adresse_livraison' => 'required_if:livraison,1|string|max:500|nullable',
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
        ], 201);
    }

    public function verifierOtp(Request $request, $transaction_id)
    {
        // Validation de l'OTP
        $validator = Validator::make($request->all(), [
            'otp' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $transaction = Transaction::findOrFail($transaction_id);

        if ($transaction->statut === 'effectuee') {
            return response()->json(['message' => 'Paiement déjà validé.'], 200);
        }

        if ($transaction->otp === $request->otp) {
            $transaction->update([
                'statut' => 'effectuee',
                'verifie_a' => now(),
            ]);

            // Mettre à jour le stock du produit
            $commande = $transaction->commande;
            $produit = $commande->produit;
            $produit->stock -= $commande->quantite;
            $produit->save();

            // Mettre à jour le statut de la commande
            $commande->update(['statut' => 'en_cours']);

            return response()->json(['message' => 'Paiement via Orange Money validé avec succès.'], 200);
        } else {
            return response()->json(['error' => 'OTP incorrect.'], 401);
        }
    }

    public function telechargerRecepisse(Request $request, $commande_id)
    {
        $commande = CommandeVetement::with(['transaction', 'produit', 'compagnie'])
            ->where('id', $commande_id)
            ->firstOrFail();

        if (!$commande->transaction || $commande->transaction->statut !== 'effectuee') {
            return response()->json(['message' => 'Paiement non validé.'], 403);
        }

        // Générer un numéro de reçu unique si non existant
        if (!$commande->numero_recu) {
            $dernierRecepisse = CommandeVetement::whereNotNull('numero_recu')
                ->orderBy('numero_recu', 'desc')
                ->pluck('numero_recu')
                ->first();

            $dernierNumero = $dernierRecepisse
                ? intval(preg_replace('/\D/', '', $dernierRecepisse))
                : 0;

            $nouveauNumero = $dernierNumero + 1;
            $commande->numero_recu = 'N°' . str_pad($nouveauNumero, 7, '0', STR_PAD_LEFT);
            $commande->save();
        }

        $pdf = PDF::loadView('pdf.recu', ['commande' => $commande]);

        return $pdf->download("recepisse-commande-{$commande->id}.pdf");
    }
}
