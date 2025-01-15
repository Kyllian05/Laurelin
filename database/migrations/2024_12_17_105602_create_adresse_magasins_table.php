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
            CREATE TABLE AdresseMagasins (
                ID INT AUTO_INCREMENT PRIMARY KEY,
                ID_VILLE INT NOT NULL,
                ADRESSE VARCHAR(255) NOT NULL
            )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('AdresseMagasins');
    }
};
