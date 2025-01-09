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
            CREATE TABLE Adresse (
                ID INT PRIMARY KEY AUTO_INCREMENT,
                NUM_RUE INT NOT NULL,
                NOM_RUE VARCHAR(255)NOT NULL,
                ID_UTILISATEUR INT,
                ID_VILLE INT
            )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Adresse');
    }
};
