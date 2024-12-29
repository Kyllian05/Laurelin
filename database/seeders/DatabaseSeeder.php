<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CategorieSeeder::class,
            ImportDonneeProduitSeeder::class,
            UtilisateurSeeder::class,
            VilleSeeder::class,
            AdresseSeeder::class,
            CommandeSeeder::class,
        ]);
    }
}
