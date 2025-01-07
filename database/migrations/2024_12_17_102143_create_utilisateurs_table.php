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
            CREATE TABLE Utilisateur (
                ID INT PRIMARY KEY AUTO_INCREMENT,
                EMAIL VARCHAR(255) NOT NULL UNIQUE,
                PASSWORD VARCHAR(255) NOT NULL,
                PRENOM VARCHAR(255) NOT NULL,
                NOM VARCHAR(255) NOT NULL,
                TELEPHONE VARCHAR(255) UNIQUE,
                PRIVILEGE INT DEFAULT 0,
                TOKEN VARCHAR(32) UNIQUE,
                TOKENGEN DATETIME,
                CODE INT UNIQUE,
                CODEGEN DATETIME
                )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Utilisateur');
    }
};
