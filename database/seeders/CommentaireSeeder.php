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
            ["CONTENU" => "Ce bracelet en argent avec des diamants est magnifique, il attire tous les regards.", "ID_PRODUIT" => 18, "ID_UTILISATEUR" => 1],
            ["CONTENU" => "J'adore ce collier avec pendentif en forme rond, il est orné d'un plus gros diamant cela fait sensation. Bravo!!!", "ID_PRODUIT" => 1, "ID_UTILISATEUR" => 1],
            ["CONTENU" => "Ce collier ornée d'emeraude verte a fait sensation quand je l’ai offerte.", "ID_PRODUIT" => 11, "ID_UTILISATEUR" => 2],
            ["CONTENU" => "Ce collier dorées est superbe et très confortables à porter toute la journée.", "ID_PRODUIT" => 5, "ID_UTILISATEUR" => 2],
            ["CONTENU" => "Le design de cette haine souple est simple.", "ID_PRODUIT" => 6, "ID_UTILISATEUR" => 1],
            ["CONTENU" => "Très contente de ce collier grain de café, le design est vraiment exceptionnel, il a illuminé ma soirée.", "ID_PRODUIT" => 8, "ID_UTILISATEUR" => 2],
            ["CONTENU" => "J’ai acheté ce pendentif en or pour l’anniversaire de ma sœur, elle l’a adoré.", "ID_PRODUIT" => 3, "ID_UTILISATEUR" => 1],
            ["CONTENU" => "Ce collier pluie est magnifique, la taille des gouttes sont parfaites pour un look chic de soirées.", "ID_PRODUIT" => 9, "ID_UTILISATEUR" => 2],
            ["CONTENU" => "Le collier est arrivé rapidement, et la chaîne est très fine et élégante.", "ID_PRODUIT" => 4, "ID_UTILISATEUR" => 1],
            ["CONTENU" => "Ce collier, malgré que un peu rigide, est vraiment beau a porté surtout dans le coloris rose.", "ID_PRODUIT" => 7, "ID_UTILISATEUR" => 2],
        ]);
    }
}
