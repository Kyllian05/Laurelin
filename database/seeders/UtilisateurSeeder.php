<?php

namespace Database\Seeders;

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
        DB::table('Utilisateur')->insert([
            'EMAIL' => "admin@admin.com",
            'PASSWORD' => hash("sha256","admin"),
            "PRENOM" => "Admin",
            "NOM" => "Admin",
            "PRIVILEGE" => 1,
            "TOKEN"=>\App\Models\Utilisateur::generateToken(),
            "TOKENGEN"=>date ('Y-m-d H:i:s', time())
        ]);
    }
}
