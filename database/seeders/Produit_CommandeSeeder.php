<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Produit_CommandeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('Produit_Commande')->insert([
            "QUANTITE" => 2,
            "TAILLE" => 0,
            "ID_PRODUIT" => 1,
            "ID_COMMANDE" => 1,
            "PRIX" => \App\Models\Produit::getProduct(1)["PRIX"]
        ]);

        DB::table('Produit_Commande')->insert([
            "QUANTITE" => 1,
            "TAILLE" => 0,
            "ID_PRODUIT" => 5,
            "ID_COMMANDE" => 2,
            "PRIX" => \App\Models\Produit::getProduct(5)["PRIX"]
        ]);

        DB::table('Produit_Commande')->insert([
            "QUANTITE" => 1,
            "TAILLE" => 0,
            "ID_PRODUIT" => 7,
            "ID_COMMANDE" => 2,
            "PRIX" => \App\Models\Produit::getProduct(7)["PRIX"]
        ]);

        DB::table('Produit_Commande')->insert([
            "QUANTITE" => 3,
            "TAILLE" => 0,
            "ID_PRODUIT" => 1,
            "ID_COMMANDE" => 3,
            "PRIX" => \App\Models\Produit::getProduct(1)["PRIX"]
        ]);
        DB::table('Produit_Commande')->insert([
            "QUANTITE" => 1,
            "TAILLE" => 0,
            "ID_PRODUIT" => 17,
            "ID_COMMANDE" => 4,
        ]);
    }
}
