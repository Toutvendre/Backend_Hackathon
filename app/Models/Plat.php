<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plat extends Model
{
    protected $fillable = [
        'nom',
        'prix',
        'stock',
        'description',
        'image',
        'disponibilite',
        'temps_preparation',
        'ingredients',
        'categorie_plat_id',
        'compagnie_id',
    ];

    public function categorie()
    {
        return $this->belongsTo(CategoriePlat::class, 'categorie_plat_id');
    }

    public function compagnie()
    {
        return $this->belongsTo(Compagnie::class);
    }

    public function commandes()
    {
        return $this->hasMany(CommandePlat::class, 'plat_id');
    }
}
