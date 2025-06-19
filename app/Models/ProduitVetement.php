<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProduitVetement extends Model
{
    protected $fillable = [
        'nom',
        'prix',
        'stock',
        'description',
        'image',
        'vetement_categorie_id',
        'vetement_sous_categorie_id',
        'compagnie_id',
    ];

    public function categorie()
    {
        return $this->belongsTo(VetementCategory::class, 'vetement_categorie_id');
    }

    public function sousCategorie()
    {
        return $this->belongsTo(VetementSousCategorie::class, 'vetement_sous_categorie_id');
    }

    public function compagnie()
    {
        return $this->belongsTo(Compagnie::class);
    }

    public function commandes()
    {
        return $this->hasMany(CommandeVetement::class, 'vetement_produit_id');
    }
}
