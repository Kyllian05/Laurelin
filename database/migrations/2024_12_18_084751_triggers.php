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
            Create trigger IF NOT EXISTS check_prix after insert on Produit FOR EACH ROW
            begin 
                if NEW.PRIX < 0.0 then
                SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Le prix ne peut pas être négatif';
                END IF;
            END;
        ");

        DB::statement("
            Create TRIGGER annee_produit after insert on Produit FOR EACH ROW
            begin 
                if 1964 > NEW.ANNEE_CREATION or NEW.ANNEE_CREATION > 2025 then
                SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'le produit ne peut pas avoir été créer avant l_entreprise ou dans le futur';
                END IF;
            END;
        ");
        /*
        DB::statement("
            Create or replace TRIGGER prix after insert or update on produit
            begin 
                if :new.prix < 0.0 then
                raise_application_error(-20000, 'le prix ne peut pas etre négatif')
        ");*/
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
