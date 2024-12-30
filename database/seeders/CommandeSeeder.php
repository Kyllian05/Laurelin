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
            "ETAT" => 0,
            "MODE_LIVRAISON" => "domicile",
            "ID_UTILISATEUR" => 1,
            "ID_ADRESSE" => 1,
        ]);
        DB::table('Commande')->insert([
            "DATE" => date ('Y-m-d H:i:s', time()-1000),
            "ETAT" => 1,
            "MODE_LIVRAISON" => "domicile",
            "ID_UTILISATEUR" => 1,
            "ID_ADRESSE" => 1,
        ]);
    }
}
