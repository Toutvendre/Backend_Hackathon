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
        'quantite',
        'prix_total',
        'statut',
    ];

    // Produit commandé
    public function produit()
    {
        return $this->belongsTo(ProduitVetement::class, 'vetement_produit_id');
    }

    // Compagnie vendeuse
    public function compagnie()
    {
        return $this->belongsTo(Compagnie::class);
    }
}
