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

        /* OK */
        DB::statement("
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


        /* OK */
        DB::statement("
            CREATE OR REPLACE TRIGGER ANNULLATION_COMMANDE_STOCK
            AFTER DELETE ON Produit_Commande
            FOR EACH ROW
            BEGIN
                UPDATE Produit
                SET STOCK = STOCK + OLD.QUANTITE
                WHERE Produit.ID = OLD.ID_PRODUIT;
            END;
        ");


        /* OK */
        DB::statement("
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


        /* OK */
        DB::statement("
            CREATE OR REPLACE TRIGGER MISE_A_JOUR_STOCK
            AFTER INSERT ON Produit_Commande
            FOR EACH ROW
            BEGIN
                UPDATE Produit
                SET STOCK = STOCK - NEW.QUANTITE
                WHERE Produit.ID = NEW.ID_PRODUIT;
            END;
        ");


        /* OK */
        DB::statement("
            CREATE OR REPLACE TRIGGER MISE_A_JOUR_ETAT
            AFTER INSERT ON Produit_Commande
            FOR EACH ROW
            BEGIN
                DECLARE STOCK_STATUS INT;
                SELECT STOCK INTO STOCK_STATUS FROM Produit WHERE Produit.ID = NEW.ID_PRODUIT;
                IF STOCK_STATUS = 0 THEN
                    UPDATE Produit SET ETAT = 'Produit indisponible' WHERE STOCK = STOCK_STATUS;
                END IF;
            END;
        ");


        /* OK */
        DB::statement("
            CREATE OR REPLACE TRIGGER VERIF_COMMENTAIRE
            BEFORE INSERT ON Commentaire
            FOR EACH ROW
            BEGIN
                IF TRIM(NEW.CONTENU) = '' THEN
                    SIGNAL SQLSTATE '45009' SET MESSAGE_TEXT = 'Le commentaire ne peut être vide.';
                END IF;
            END;
        ");


        /* OK */
        DB::statement("
            CREATE OR REPLACE TRIGGER NO_DELETE_USER_COMMANDES
            BEFORE DELETE ON Utilisateur
            FOR EACH ROW
            BEGIN
                DECLARE nb_commandes INT;
                SELECT COUNT(*) INTO nb_commandes
                FROM Commande
                WHERE Commande.ID_UTILISATEUR = OLD.ID;

                IF nb_commandes > 0 THEN
                    SIGNAL SQLSTATE '45010' SET MESSAGE_TEXT = 'Impossible de supprimer un utilisateur ayant des commandes.';
                END IF;
            END;
        ");


        /* OK */
        DB::statement("
            CREATE OR REPLACE TRIGGER SET_DATE_COMMANDE
            BEFORE INSERT ON Commande
            FOR EACH ROW
            BEGIN
                IF NEW.DATE IS NULL THEN
                    SET NEW.DATE = NOW();
                END IF;
            END;
        ");


        /* OK */
        // Insert
        DB::statement("
            CREATE TRIGGER VALID_NUMBER_INSERT
            BEFORE INSERT ON Utilisateur
            FOR EACH ROW
            BEGIN
                IF NEW.TELEPHONE NOT REGEXP '^\\\\+[0-9]{1,15}$' THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Le numéro de téléphone peut être composé au maximum de 15 chiffres.';
                END IF;
            END;
        ");
        // Update
        DB::statement("
            CREATE TRIGGER VALID_NUMBER_UPDATE
            BEFORE UPDATE ON Utilisateur
            FOR EACH ROW
            BEGIN
                IF NEW.TELEPHONE NOT REGEXP '^\\\\+[0-9]{1,15}$' THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Le numéro de téléphone peut être composé au maximum de 15 chiffres.';
                END IF;
            END;
        ");


        /* OK */
        // Insert
        DB::statement("
            CREATE OR REPLACE TRIGGER VALID_EMAIL_INSERT
            BEFORE INSERT ON Utilisateur
            FOR EACH ROW
            BEGIN
                IF NEW.EMAIL NOT REGEXP '^[[:alnum:]_.-]+@([[:alnum:]-]+\\.)+[[:alnum:]]{2,4}$' THEN
                    SIGNAL SQLSTATE '45016'
                    SET MESSAGE_TEXT = 'Adresse EMAIL non valide.';
                END IF;
            END;
        ");
        // Update
        DB::statement("
            CREATE OR REPLACE TRIGGER VALID_EMAIL_UPDATE
            BEFORE UPDATE ON Utilisateur
            FOR EACH ROW
            BEGIN
                IF NEW.EMAIL NOT REGEXP '^[[:alnum:]_.-]+@([[:alnum:]-]+\\.)+[[:alnum:]]{2,4}$' THEN
                    SIGNAL SQLSTATE '45016'
                    SET MESSAGE_TEXT = 'Adresse EMAIL non valide.';
                END IF;
            END;
        ");
    }



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
        DB::statement("DROP TRIGGER IF EXISTS VERIF_COMMENTAIRE");
        DB::statement("DROP TRIGGER IF EXISTS NO_DELETE_USER_COMMANDES");
        DB::statement("DROP TRIGGER IF EXISTS SET_DATE_COMMANDE");
        DB::statement("DROP TRIGGER IF EXISTS VALID_NUMBER_INSERT");
        DB::statement("DROP TRIGGER IF EXISTS VALID_NUMBER_UPDATE");
        DB::statement("DROP TRIGGER IF EXISTS VALID_EMAIL_INSERT");
        DB::statement("DROP TRIGGER IF EXISTS VALID_EMAIL_UPDATE");
    }
};
