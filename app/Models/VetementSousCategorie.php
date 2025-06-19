<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VetementSousCategorie extends Model
{
    protected $fillable = ['nom', 'vetement_category_id'];

    public function categorie()
    {
        return $this->belongsTo(VetementCategory::class, 'vetement_category_id');
    }

    public function produits()
    {
        return $this->hasMany(ProduitVetement::class);
    }
}
