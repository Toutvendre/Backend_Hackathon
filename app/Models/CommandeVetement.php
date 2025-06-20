<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommandeVetement extends Model
{
    protected $fillable = [
        'vetement_produit_id',
        'compagnie_id',
        'client_nom',
        'client_telephone',
        'livraison',
        'adresse_livraison',
        'notes',
        'quantite',
        'prix_total',
        'statut',
        'date_commande',
        'numero_recu',
    ];

    public function produit()
    {
        return $this->belongsTo(ProduitVetement::class, 'vetement_produit_id');
    }

    public function compagnie()
    {
        return $this->belongsTo(Compagnie::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'commande_vetement_id');
    }
}
