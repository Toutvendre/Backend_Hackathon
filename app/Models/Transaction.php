<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'commande_vetement_id',
        'code_transaction',
        'telephone',
        'montant',
        'otp',
        'statut',
        'verifie_a',
    ];

    public function commande()
    {
        return $this->belongsTo(CommandeVetement::class, 'commande_vetement_id');
    }
}
