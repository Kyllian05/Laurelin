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
            CollectionSeeder::class,
            ImportDonneeProduitSeeder::class,
            ImportDonneeVilleFrance::class,
            ImportDonneeAdresseMagasins::class,
            UtilisateurSeeder::class,
            AdresseSeeder::class,
            CommandeSeeder::class,
            ImageSeeder::class,
            Produit_CommandeSeeder::class,
            FavorisSeeder::class,
        ]);
    }
}
