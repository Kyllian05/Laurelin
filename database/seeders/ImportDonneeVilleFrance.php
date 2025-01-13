<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImportDonneeVilleFrance extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Chemin du fichier JSON
        $filePath = database_path('data/france.json');

        // Charger et lire le fichier JSON
        $jsonData = file_get_contents($filePath);
        $data = json_decode($jsonData, true);

        foreach ($data as $row) {
            // Insérer les données dans la table Ville
            DB::table('Ville')->insertOrIgnore([
                'ID' => $row['Code_commune_INSEE'],
                'NOM' => trim($row['Nom_commune']),
                'CODE_POSTAL' => $row['Code_postal'],
            ]);
        }
    }
}
