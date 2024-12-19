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

/* OK */DB::statement("
            CREATE OR REPLACE TRIGGER STOCK_INSUFFISANT
            BEFORE INSERT ON Produit_Commande
            FOR EACH ROW
            BEGIN
                DECLARE current_stock INT;
                SELECT stock INTO current_stock FROM Produit WHERE Produit.ID = NEW.ID_PRODUIT;
                IF current_stock < NEW.QUANTITE THEN
                    SIGNAL SQLSTATE '45003' SET MESSAGE_TEXT = 'Stock insuffisant pour ce produit.';
                END IF;
            END;

        ");


/* OK */DB::statement("
            CREATE OR REPLACE TRIGGER ANNULLATION_COMMANDE_STOCK
            AFTER DELETE ON Produit_Commande
            FOR EACH ROW
            BEGIN
                UPDATE Produit
                SET STOCK = STOCK + OLD.QUANTITE
                WHERE Produit.ID = OLD.ID_PRODUIT;
            END;
        ");


/* OK */DB::statement("
            CREATE OR REPLACE TRIGGER COMMENTAIRE_PRODUIT_RETIRE
            BEFORE INSERT ON Commentaire
            FOR EACH ROW
            BEGIN
                DECLARE product_count INT;
                SELECT COUNT(*) INTO product_count FROM Produit WHERE Produit.ID = NEW.ID_PRODUIT;
                IF product_count = 0 THEN
                    SIGNAL SQLSTATE '45008' SET MESSAGE_TEXT = 'Impossible d’ajouter un commentaire sur un produit retiré ou inexistant.';
                END IF;
            END;
        ");


/* OK */DB::statement("
            CREATE OR REPLACE TRIGGER MISE_A_JOUR_STOCK
            AFTER INSERT ON Produit_Commande
            FOR EACH ROW
            BEGIN
                UPDATE Produit
                SET STOCK = STOCK - NEW.QUANTITE
                WHERE Produit.ID = NEW.ID_PRODUIT;
            END;
        ");


/* OK */DB::statement("
            CREATE OR REPLACE TRIGGER MISE_A_JOUR_ETAT
            AFTER INSERT ON Produit_Commande
            FOR EACH ROW
            BEGIN
                DECLARE STOCK_STATUS INT;
                SELECT STOCK INTO STOCK_STATUS FROM Produit WHERE Produit.ID = NEW.ID_PRODUIT;
                IF STOCK_STATUS = 0 THEN
                    UPDATE Produit SET ETAT = 'Produit Plus Disponible' WHERE STOCK = STOCK_STATUS;
                END IF;
            END;
        ");
    }

    /* EXEMPLE

    3. Trigger pour vérifier qu'un utilisateur ne laisse pas de commentaire vide AVEC ESPACE AUSSI

    11. Trigger pour interdire la suppression d’un utilisateur s’il a passé des commandes

    14. Trigger pour s’assurer que le téléphone utilisateur a un format valide

    15. Trigger pour ajouter automatiquement une date à une commande
    */


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP TRIGGER IF EXISTS MISE_A_JOUR_STOCK");
        DB::statement("DROP TRIGGER IF EXISTS STOCK_INSUFFISANT");
        DB::statement("DROP TRIGGER IF EXISTS ANNULLATION_COMMANDE_STOCK");
        DB::statement("DROP TRIGGER IF EXISTS MISE_A_JOUR_ETAT");
        DB::statement("DROP TRIGGER IF EXISTS COMMENTAIRE_PRODUIT_RETIRE");
    }
};
