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
            'PASSWORD' => "$2y$10\$cC5T7c4oy4G397TgUhHpZOyIB1ZbO1wUBHw3W1o8Ja0pqMbYBnuBy", // admin
            'PRENOM' => "Admin",
            'NOM' => "Admin",
            'TELEPHONE' => "0606060606",
            'PRIVILEGE' => 1,
        ]);

        // Test user
        DB::table('Utilisateur')->insert([
            'EMAIL' => "user@user.com",
            'PASSWORD' => "$2y$10$8s3ERuMJ8eg2Rop4jR.rxemHyRu5gEStA.z/m4L7DELISlW4DarN2", // user
            'PRENOM' => "User",
            'NOM' => "User",
            'TELEPHONE' => "0707070707",
            'PRIVILEGE' => 0,
        ]);
    }
}
