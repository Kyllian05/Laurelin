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
        // ---
        // Procédures table catégorie et collection avec produit
        // ---
        DB::statement("
            CREATE OR REPLACE PROCEDURE select_product_categories (IN name VARCHAR(255))
            BEGIN
                SELECT p.ID, p.NOM, p.MATERIAUX, p.PRIX, p.ETAT, p.ANNEE_CREATION, MIN(i.URL) as URL FROM Produit p, Image i WHERE p.ID_CATEGORIE in (
                    SELECT ID FROM categorie WHERE NOM = name
                ) and i.ID_PRODUIT = p.ID
                GROUP BY p.ID, p.NOM, p.MATERIAUX, p.PRIX, p.ETAT, p.ANNEE_CREATION;
            END;
        ");

        DB::statement("
            CREATE OR REPLACE PROCEDURE select_product_collection (IN name VARCHAR(255))
            BEGIN
                SELECT p.ID, p.NOM, p.MATERIAUX, p.PRIX, p.ETAT, p.ANNEE_CREATION, MIN(i.URL) as URL FROM Produit p, Image i WHERE p.ID_COLLECTION in (
                    SELECT ID FROM Collection WHERE NOM = name
                ) and i.ID_PRODUIT = p.ID
                GROUP BY p.ID, p.NOM, p.MATERIAUX, p.PRIX, p.ETAT, p.ANNEE_CREATION;
            END;
        ");

        // ---
        // Procédures table Image
        // ---

        DB::statement("
            CREATE OR REPLACE PROCEDURE select_all_images (IN id INT)
            BEGIN
                SELECT URL FROM Image WHERE ID_PRODUIT = id;
            END;
        ");

        DB::statement("
            CREATE OR REPLACE PROCEDURE select_one_image (IN id INT)
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
        DB::statement("DROP PROCEDURE IF EXISTS select_product_collection;");
        DB::statement("DROP PROCEDURE IF EXISTS select_product_categories;");
        DB::statement("DROP PROCEDURE IF EXISTS select_all_images;");
        DB::statement("DROP PROCEDURE IF EXISTS select_one_image;");
    }
};
