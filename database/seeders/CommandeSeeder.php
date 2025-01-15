<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommandeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('Commande')->insert([
            "DATE" => date ('Y-m-d H:i:s', time()),
            "ETAT" => "Terminée",
            "MODE_LIVRAISON" => "domicile",
            "ID_UTILISATEUR" => 1,
            "ID_ADRESSE" => 1,
        ]);
        DB::table('Commande')->insert([
            "DATE" => date ('Y-m-d H:i:s', time()-1000),
            "ETAT" => "Expédiée",
            "MODE_LIVRAISON" => "domicile",
            "ID_UTILISATEUR" => 1,
            "ID_ADRESSE" => 2,
        ]);
        DB::table('Commande')->insert([
            "DATE" => date ('Y-m-d H:i:s', time()-100000),
            "ETAT" => "Terminée",
            "MODE_LIVRAISON" => "domicile",
            "ID_UTILISATEUR" => 1,
            "ID_ADRESSE" => 1,
        ]);
        DB::table('Commande')->insert([
            "DATE" => date ('Y-m-d H:i:s', time()),
            "ETAT" => "Panier",
            "ID_UTILISATEUR" => 1,
        ]);
        DB::table('Commande')->insert([
            "DATE" => date ('Y-m-d H:i:s', time()),
            "ETAT" => "Terminée",
            "MODE_LIVRAISON" => "magasin",
            "ID_UTILISATEUR" => 1,
            "ID_MAGASIN" => 1,
        ]);
    }
}
