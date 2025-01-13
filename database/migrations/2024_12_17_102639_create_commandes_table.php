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
            CREATE TABLE Commande (
                ID INT PRIMARY KEY AUTO_INCREMENT,
                DATE DATE NOT NULL,
                ETAT VARCHAR(255) NOT NULL,
                MODE_LIVRAISON VARCHAR(255),
                ID_UTILISATEUR INT NOT NULL,
                ID_ADRESSE INT,
                ID_ADRESSE_MAGASINS INT
            )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Commande');
    }
};
