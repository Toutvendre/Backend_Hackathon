<?php

namespace App\Models;

use App\Models\Compagnie;

use Illuminate\Database\Eloquent\Model;

class TypeCategorie extends Model
{
    protected $fillable = ['nom'];

    public function compagnies()
    {
        return $this->hasMany(Compagnie::class, 'type_categorie_id');
    }

    // Relation vers les catégories vêtements (VetementCategory)
    public function vetementCategories()
    {
        return $this->hasMany(VetementCategory::class, 'type_categorie_id');
    }
}
