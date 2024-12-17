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
                NUM_RUE INT,
                NOM_RUE VARCHAR(255),
                ID_UTILISATEUR INT,
                CODE_POSTAL INT
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
