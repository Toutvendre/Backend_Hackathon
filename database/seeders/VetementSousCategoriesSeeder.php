<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VetementSousCategorie;
use App\Models\VetementCategory;

class VetementSousCategoriesSeeder extends Seeder
{
    public function run()
    {
        // Récupérer les catégories vêtement
        $vetementTrad = VetementCategory::where('nom', 'Vêtement traditionnel')->first();
        $vetementShop = VetementCategory::where('nom', 'Vêtement shop')->first();

        if (!$vetementTrad || !$vetementShop) {
            $this->command->error("Les catégories 'Vêtement traditionnel' ou 'Vêtement shop' n'existent pas.");
            return;
        }

        $sousCategories = [
            // Pour 'Vêtement traditionnel'
            ['nom' => 'Robe pagne', 'vetement_categorie_id' => $vetementTrad->id],
            ['nom' => 'Boubou homme', 'vetement_categorie_id' => $vetementTrad->id],
            ['nom' => 'Boubou femme', 'vetement_categorie_id' => $vetementTrad->id],
            ['nom' => 'Complet traditionnel', 'vetement_categorie_id' => $vetementTrad->id],

            // Pour 'Vêtement shop'
            ['nom' => 'T-shirt', 'vetement_categorie_id' => $vetementShop->id],
            ['nom' => 'Pantalon jeans', 'vetement_categorie_id' => $vetementShop->id],
            ['nom' => 'Chemise', 'vetement_categorie_id' => $vetementShop->id],
            ['nom' => 'Robe moderne', 'vetement_categorie_id' => $vetementShop->id],
        ];

        foreach ($sousCategories as $sousCat) {
            VetementSousCategorie::create($sousCat);
        }
    }
}
