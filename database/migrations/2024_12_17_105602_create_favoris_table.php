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
        //TODO ajouter des triggers etc pour cette table
        DB::Statement("CREATE TABLE Favoris (
            ID_PRODUIT INT NOT NULL,
            ID_UTILISATEUR INT NOT NULL
            )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Favoris');
    }
};
