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
                EMAIL VARCHAR(255),
                PASSWORD VARCHAR(255),
                PRENOM VARCHAR(255),
                NOM VARCHAR(255),
                TELEPHONE VARCHAR(255),
                PRIVILEGE INT
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
