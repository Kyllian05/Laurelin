<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommentaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('Commentaire')->insert([
            "CONTENU" => "Super produit",
            "ID_PRODUIT" => 18,
            "ID_UTILISATEUR" => 1,
        ]);
        DB::table('Commentaire')->insert([
            "CONTENU" => "Je recommande",
            "ID_PRODUIT" => 1,
            "ID_UTILISATEUR" => 1,
        ]);
        DB::table('Commentaire')->insert([
            "CONTENU" => "Un Très bon Produit, parfait pour ma Femme",
            "ID_PRODUIT" => 11,
            "ID_UTILISATEUR" => 2,
        ]);
        
    }
}
