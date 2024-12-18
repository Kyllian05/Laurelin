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
            CREATE TABLE image (
                URL VARCHAR(255) PRIMARY KEY,
                ID_PRODUIT INT
            )
        ");

        DB::statement("
            ALTER TABLE image
                ADD CONSTRAINT fk_produit
                    FOREIGN KEY (ID_PRODUIT) REFERENCES Produit(ID)
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('image');
    }
};
