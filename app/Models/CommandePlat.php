<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommandePlat extends Model
{
    protected $fillable = [
        'plat_id',
        'user_id',
        'quantite',
        'prix_total',
        'statut', // exemple : 'en_attente', 'en_livraison', 'livré'
    ];

    public function plat()
    {
        return $this->belongsTo(Plat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
