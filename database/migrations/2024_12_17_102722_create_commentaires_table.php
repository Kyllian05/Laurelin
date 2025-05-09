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
            CREATE TABLE Commentaire (
                CONTENU VARCHAR(255),
                ID_UTILISATEUR INT NOT NULL,
                ID_PRODUIT INT NOT NULL,
                DATE DATE NOT NULL
            )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Commentaire');
    }
};
