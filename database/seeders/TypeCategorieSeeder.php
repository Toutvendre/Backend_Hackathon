<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TypeCategorie;

class TypeCategorieSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Restaurant',
            'Vêtement',
            'Santé',
            'Électronique',
            'Transport',
            'Immobilier',
            'Éducation',
            'Beauté',
            'Loisirs',
            'Services divers'
        ];

        foreach ($categories as $categorie) {
            TypeCategorie::create(['nom' => $categorie]);
        }
    }
}
