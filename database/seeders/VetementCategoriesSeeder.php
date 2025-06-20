<?php

// VetementCategoriesSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VetementCategory;
use App\Models\TypeCategorie;

class VetementCategoriesSeeder extends Seeder
{
    public function run()
    {
        // Trouver l'id de la catégorie métier 'Vêtement'
        $typeVetement = TypeCategorie::where('nom', 'Vêtement')->first();

        if (!$typeVetement) {
            $this->command->error("La catégorie 'Vêtement' n'existe pas dans TypeCategorie.");
            return;
        }

        $categories = [
            ['nom' => 'Vêtement traditionnel', 'type_categorie_id' => $typeVetement->id],
            ['nom' => 'Vêtement shop', 'type_categorie_id' => $typeVetement->id],
        ];

        foreach ($categories as $cat) {
            VetementCategory::create($cat);
        }
    }
}
