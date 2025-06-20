<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VetementCategory extends Model
{
    protected $fillable = ['nom'];


    public function typeCategorie()
    {
        return $this->belongsTo(TypeCategorie::class, 'type_categorie_id');
    }

    public function sousCategories()
    {
        return $this->hasMany(VetementSousCategorie::class, 'vetement_categorie_id');
    }

    public function produits()
    {
        return $this->hasMany(ProduitVetement::class);
    }
}
