<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VilleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('Ville')->insert([
            "NOM"=>"Paris",
            "CODE_POSTAL" => 75116
        ]);

        DB::table('Ville')->insert([
            "NOM"=>"Nantes",
            "CODE_POSTAL" => 44700
        ]);

        DB::table('Ville')->insert([
            "NOM"=>"Nantes",
            "CODE_POSTAL" => 44200
        ]);
    }
}
