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
        // Exécuter du SQL brut
        DB::statement("
            CREATE TABLE categorie (
                ID INT PRIMARY KEY AUTO_INCREMENT,
                NOM VARCHAR(255) NOT NULL
            )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorie');
    }
};
