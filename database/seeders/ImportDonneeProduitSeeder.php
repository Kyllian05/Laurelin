<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class ImportDonneeProduitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Chemin du fichier CSV
        $filePath = database_path('data/DonneeProduit.csv');

        // Charger et lire le fichier CSV
        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setHeaderOffset(0); // Définit la première ligne comme en-tête

        foreach ($csv as $row) {
            // Préparer les données avant insertion
            DB::table('Produit')->insert([
                'NOM' => trim($row['NOM']) ?? null,
                'MATERIAUX'=> $row['MATERIAUX'] ?? null,
                'DESCRIPTION' => $row['DESCRIPTION'] ?? null,
                'PRIX' => str_replace(',', '.', str_replace(' ', '', $row['PRIX']) )?? null, // Supprime les espaces des nombres
                'ANNEE_CREATION' => $row['ANNEE_CREATION'] ?? null,
                'ETAT' => $row['ETAT'] ?? null,
                'STOCK' => $row['STOCK'] ?? 0,
                'ID_CATEGORIE' => $row['ID_CATEGORIE'] ?? null,
                'ID_COLLECTION' => isset($row['ID_COLLECTION']) && $row['ID_COLLECTION'] !== '' ? $row['ID_COLLECTION'] : null,
            ]);
        }
    }
}
