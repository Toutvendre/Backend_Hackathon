<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voyage extends Model
{
    protected $fillable = [
        'nom',
        'type_transport',
        'type_trajet',
        'ville_depart',
        'ville_arrivee',
        'heure_depart',
        'heure_arrivee',
        'prix',
        'localisation',
        'compagnie_id',
    ];
    
    public function compagnie()
{
    return $this->belongsTo(Compagnie::class, 'compagnie_id');
}

}
