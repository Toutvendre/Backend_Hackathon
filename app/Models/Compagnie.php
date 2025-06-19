<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\TypeCategorie;


class Compagnie extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'nom',
        'logo',
        'description',
        'telephone',
        'email',
        'adresse',
        'site_web',
        'profil_complet',
        'CMPID',
        'mot_de_passe',
        'type_categorie_id',
    ];

    protected $hidden = ['mot_de_passe'];

    /**
     * Relation avec la catégorie de la compagnie.
     */
    public function typeCategorie()
    {
        return $this->belongsTo(TypeCategorie::class, 'type_categorie_id');
    }
}
