<?php

namespace Database\Seeders;

use App\Domain\Produit\Repositories\ProduitRepository;
use App\Domain\Utilisateur\Repositories\FavorisRepository;
use App\Domain\Utilisateur\Repositories\UtilisateurRepository;
use App\Domain\Utilisateur\Services\UtilisateurService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UtilisateurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $service = new UtilisateurService(new UtilisateurRepository(), new FavorisRepository(new ProduitRepository()));

        DB::table('Utilisateur')->insert([
            'EMAIL' => "admin@admin.com",
            'PASSWORD' => hash("sha256","admin"),
            "PRENOM" => "Admin",
            "NOM" => "Admin",
            "PRIVILEGE" => 1,
            "TOKEN"=> $service->generateToken(),
            "TOKENGEN"=>date ('Y-m-d H:i:s', time())
        ]);

        DB::table('Utilisateur')->insert([
            'EMAIL' => "jeffreyscht@gmail.com",
            'PASSWORD' => hash("sha256","Jeffrey*123"),
            "PRENOM" => "Jeffrey",
            "NOM" => "Souchet",
            "PRIVILEGE" => 0,
            "TOKEN"=> $service->generateToken(),
            "TOKENGEN"=>date ('Y-m-d H:i:s', time())
        ]);
    }
}
