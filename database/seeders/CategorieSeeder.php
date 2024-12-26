<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categorie')->insert([
            'NOM' => "Colliers"
        ]);

        DB::table('categorie')->insert([
            'NOM' => "Bracelets"
        ]);

        DB::table('categorie')->insert([
            'NOM' => "Bagues"
        ]);

        DB::table('categorie')->insert([
            'NOM' => "Boucles d'oreilles"
        ]);

    }
}
