<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdresseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('Adresse')->insert([
        "NUM_RUE" => 22,
        "NOM_RUE" => "avenue Foch",
        "ID_UTILISATEUR" => 1,
        "CODE_POSTAL" => 75116
        ]);

        DB::table('Adresse')->insert([
            "NUM_RUE" => 47,
            "NOM_RUE" => "Route de Rennes",
            "ID_UTILISATEUR" => 1,
            "CODE_POSTAL" => 44700
        ]);

        DB::table('Adresse')->insert([
            "NUM_RUE" => 31,
            "NOM_RUE" => "Allée de la Civelière",
            "ID_UTILISATEUR" => 1,
            "CODE_POSTAL" => 44200
        ]);
    }
}
