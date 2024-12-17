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
                DESCRIPTION VARCHAR(255) NOT NULL,
                PRIX FLOAT NOT NULL,
                ANNEE_CREATION DATE,
                ETAT VARCHAR(255),
                STOCK INT,
                ID_CATEGORIE INT,
                ID_COLLECTION INT
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
