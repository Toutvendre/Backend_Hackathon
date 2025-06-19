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
}
