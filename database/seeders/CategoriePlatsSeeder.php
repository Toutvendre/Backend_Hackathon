<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategoriePlat;

class CategoriePlatsSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Plats africains',
            'Fast Food',
            'Cuisine asiatique',
            'Spécialités locales',
            'Desserts',
        ];

        foreach ($categories as $nom) {
            CategoriePlat::firstOrCreate(['nom' => $nom]);
        }
    }
}
