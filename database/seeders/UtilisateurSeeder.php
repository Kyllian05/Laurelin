<?php

namespace Database\Seeders;

use App\Domain\Utilisateur\Services\UtilisateurService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UtilisateurSeeder extends Seeder
{
    public function __construct(private readonly UtilisateurService $utilisateurService) {}

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('Utilisateur')->insert([
            'EMAIL' => "admin@admin.com",
            'PASSWORD' => hash("sha256","admin"),
            "PRENOM" => "Admin",
            "NOM" => "Admin",
            "PRIVILEGE" => 1,
            "TOKEN"=> $this->utilisateurService->generateToken(),
            "TOKENGEN"=>date ('Y-m-d H:i:s', time())
        ]);

        DB::table('Utilisateur')->insert([
            'EMAIL' => "jeffreyscht@gmail.com",
            'PASSWORD' => hash("sha256","Jeffrey*123"),
            "PRENOM" => "Jeffrey",
            "NOM" => "Souchet",
            "PRIVILEGE" => 0,
            "TOKEN"=> $this->utilisateurService->generateToken(),
            "TOKENGEN"=>date ('Y-m-d H:i:s', time())
        ]);
    }
}
