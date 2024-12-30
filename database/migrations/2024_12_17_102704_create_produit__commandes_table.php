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
            CREATE TABLE Produit_Commande (
                QUANTITE INT NOT NULL,
                TAILLE INT,
                ID_PRODUIT INT NOT NULL,
                ID_COMMANDE INT NOT NULL,
                PRIMARY KEY (ID_PRODUIT, ID_COMMANDE)
            )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Produit_Commande');
    }
};
