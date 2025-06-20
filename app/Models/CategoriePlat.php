<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriePlat extends Model
{
    protected $fillable = [
        'nom',
    ];

    public function plats()
    {
        return $this->hasMany(Plat::class, 'categorie_plat_id');
    }
}
