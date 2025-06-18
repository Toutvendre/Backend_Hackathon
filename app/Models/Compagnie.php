<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compagnie extends Model
{
    protected $fillable = ['nom'];

    // Relation : Une compagnie a plusieurs voyages
   public function voyages()
{
    return $this->hasMany(Voyage::class, 'compagnie_id');
}

}
