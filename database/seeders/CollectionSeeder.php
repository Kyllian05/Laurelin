<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('Collection')->insert([
            'NOM' => "Juste un Clou",
            'ANNEE' => 2012,
            'DESCRIPTION' => "Juste un Clou, c’est tout d’abord un clou. Un clou transformé en bijou. Des lignes sobres, radicales, sans compromis, la précision des proportions du bracelet permettent à son ovale d’étreindre le poignet.",
        ]);

        DB::table('Collection')->insert([
            'NOM' => "LOVE",
            'ANNEE' => 2021,
            'DESCRIPTION' => "LOVE est l’expression de la vision du design de Laurelin. Lignes pures jusqu’à la perfection : une collection que l'on identifie à son motif radical et novateur qui ose montrer ses vis.",
        ]);

        DB::table('Collection')->insert([
            'NOM' => "Trinity",
            'ANNEE' => 1998,
            'DESCRIPTION' => "Véritable icône du design joaillier, Trinity c’est l’équation idéale : lignes pures, justesse des proportions, précision de la forme. Pour célébrer ses 100 ans, Laurelin imagine plusieurs nouveaux designs de la bague Trinity : une version de forme coussin, une version modulaire et une version XL. Une collection à laquelle s'ajoute la réédition du bracelet XL, véritable pièce culte.",
        ]);
    }
}
