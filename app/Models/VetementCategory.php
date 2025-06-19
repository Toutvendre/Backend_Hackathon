<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VetementCategory extends Model
{
    protected $fillable = ['nom'];

    public function sousCategories()
    {
        return $this->hasMany(VetementSousCategorie::class);
    }
}
