<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Suppression des procédures pour que le "migrate:fresh" fonctionne
        DB::statement("DROP PROCEDURE IF EXISTS select_all_images;");
        DB::statement("DROP PROCEDURE IF EXISTS select_one_image;");

        // ---
        // Procédures table Image
        // ---

        DB::statement("
            CREATE PROCEDURE select_all_images (IN id INT)
            BEGIN
                SELECT URL FROM Image WHERE ID_PRODUIT = id;
            END;
        ");

        DB::statement("
            CREATE PROCEDURE select_one_image (IN id INT)
            BEGIN
                SELECT URL FROM Image WHERE ID_PRODUIT = id LIMIT 1;
            END;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP PROCEDURE IF EXISTS select_all_images;");
        DB::statement("DROP PROCEDURE IF EXISTS select_one_image;");
    }
};
