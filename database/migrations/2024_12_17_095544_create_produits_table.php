<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE TABLE Produit (
                ID INT PRIMARY KEY AUTO_INCREMENT,
                NOM VARCHAR(255) NOT NULL,
                MATERIAUX VARCHAR(255),
                DESCRIPTION TEXT NOT NULL,
                PRIX FLOAT NOT NULL,
                ANNEE_CREATION INT NOT NULL ,
                ETAT VARCHAR(255) NOT NULL,
                STOCK INT NOT NULL,
                ID_CATEGORIE INT NOT NULL,
                ID_COLLECTION INT,
                CHECK (STOCK >= 0),
                CHECK (PRIX > 0),
                CHECK ( 1964 < ANNEE_CREATION < 2025 )
            )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Produit');
    }
};
