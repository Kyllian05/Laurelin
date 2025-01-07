<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FavorisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('Favoris')->insert([
            "ID_PRODUIT" => 18,
            "ID_UTILISATEUR" => 1,
        ]);
        DB::table('Favoris')->insert([
            "ID_PRODUIT" => 1,
            "ID_UTILISATEUR" => 1,
        ]);
        DB::table('Favoris')->insert([
            "ID_PRODUIT" => 11,
            "ID_UTILISATEUR" => 1,
        ]);
    }
}
