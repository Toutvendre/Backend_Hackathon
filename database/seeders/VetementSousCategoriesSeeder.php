<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VetementSousCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('vetement_sous_categories')->insert([
            ['nom' => 'Robe pagne', 'vetement_categorie_id' => 1],
            ['nom' => 'Boubou homme', 'vetement_categorie_id' => 1],
            ['nom' => 'T-shirt', 'vetement_categorie_id' => 2],
            ['nom' => 'Pantalon jeans', 'vetement_categorie_id' => 2],
        ]);
    }
}
