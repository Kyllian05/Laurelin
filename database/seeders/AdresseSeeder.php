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
    }
}
