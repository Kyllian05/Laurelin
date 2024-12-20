<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UtilisateurSeeder extends Seeder
{
    public function run()
    {
        // Admin
        DB::table('Utilisateur')->insert([
            'EMAIL' => "admin@admin.com",
            'PASSWORD' => hash("sha256","admin"), // admin
            'PRENOM' => "Admin",
            'NOM' => "Admin",
            'TELEPHONE' => "0606060606",
            'PRIVILEGE' => 1,
        ]);

        // Test user
        DB::table('Utilisateur')->insert([
            'EMAIL' => "user@user.com",
            'PASSWORD' => hash("sha256","user"), // user
            'PRENOM' => "User",
            'NOM' => "User",
            'TELEPHONE' => "0707070707",
            'PRIVILEGE' => 0,
        ]);
    }
}
