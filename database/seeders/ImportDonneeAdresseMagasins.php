<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class ImportDonneeAdresseMagasins extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Chemin du fichier CSV
        $filePath = database_path('data/adresseMagasinsDonnees.csv');

        // Charger et lire le fichier CSV
        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setHeaderOffset(0); // Définit la première ligne comme en-tête

        foreach ($csv as $row) {
            // Préparer les données avant insertion
            DB::table('AdresseMagasins')->insert([
                'ID_VILLE'=> $row['CODE_INSEE'] ?? null,
                'ADRESSE' => $row['ADRESSE'] ?? null,
            ]);
        }
    }
}
