<?php

namespace Database\Seeders;

use App\Domain\Produit\Services\ProduitService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Produit_CommandeSeeder extends Seeder
{
    public function __construct(private ProduitService $produitService) {}

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
            "PRIX" => $this->produitService->findById(1)->prix
        ]);

        DB::table('Produit_Commande')->insert([
            "QUANTITE" => 1,
            "TAILLE" => 0,
            "ID_PRODUIT" => 5,
            "ID_COMMANDE" => 2,
            "PRIX" => $this->produitService->findById(5)->prix
        ]);

        DB::table('Produit_Commande')->insert([
            "QUANTITE" => 1,
            "TAILLE" => 0,
            "ID_PRODUIT" => 7,
            "ID_COMMANDE" => 2,
            "PRIX" => $this->produitService->findById(7)->prix
        ]);

        DB::table('Produit_Commande')->insert([
            "QUANTITE" => 3,
            "TAILLE" => 0,
            "ID_PRODUIT" => 1,
            "ID_COMMANDE" => 3,
            "PRIX" => $this->produitService->findById(1)->prix
        ]);
        DB::table('Produit_Commande')->insert([
            "QUANTITE" => 1,
            "TAILLE" => 0,
            "ID_PRODUIT" => 17,
            "ID_COMMANDE" => 4,
        ]);
        DB::table('Produit_Commande')->insert([
            "QUANTITE" => 2,
            "TAILLE" => 0,
            "ID_PRODUIT" => 15,
            "ID_COMMANDE" => 4,
        ]);
        DB::table('Produit_Commande')->insert([
            "QUANTITE" => 10,
            "TAILLE" => 0,
            "ID_PRODUIT" => 40,
            "ID_COMMANDE" => 5,
            "PRIX" => $this->produitService->findById(1)->prix
        ]);
    }
}
