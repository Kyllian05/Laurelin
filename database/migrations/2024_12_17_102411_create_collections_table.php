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
            CREATE TABLE Collection (
                ID INT PRIMARY KEY AUTO_INCREMENT,
                NOM VARCHAR(255) NOT NULL,
                ANNEE INT NOT NULL, 
                DESCRIPTION VARCHAR(255)
            )
        ");
        // Annee modifié en int pour facilité les checks
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Collection');
    }
};
