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
        
        DB::statement("
            CREATE TRIGGER update_stock_after_order
            AFTER INSERT ON Produit_Commande
            FOR EACH ROW
            BEGIN
                UPDATE Produit
                SET stock = stock - NEW.QUANTITE
                WHERE PRODUIT.ID = NEW.ID_PRODUIT;
            END;

        ");

        DB::statement("
            CREATE TRIGGER prevent_order_if_stock_insufficient
            BEFORE INSERT ON Produit_Commande
            FOR EACH ROW
            BEGIN
                DECLARE current_stock INT;
                SELECT stock INTO current_stock FROM Produit WHERE Produit.ID = NEW.ID_PRODUIT;
                IF current_stock < NEW.QUANTITE THEN
                SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Stock insuffisant pour ce produit.';
                END IF;
            END;

        ");

        DB::statement("
            CREATE TRIGGER validate_user_email
            BEFORE INSERT ON Utilisateur
            FOR EACH ROW
            BEGIN
                IF NEW.email NOT LIKE '%_@__%.__%' THEN
                SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Format de l’e-mail invalide.';
                END IF;
            END;
        ");

        DB::statement("
            CREATE TRIGGER delete_comments_on_product_deletion
            AFTER DELETE ON Produit
            FOR EACH ROW
            BEGIN
                DELETE FROM Commentaire
                WHERE Commentaire.Produit_ID = OLD.ID;
            END;
        ");  

        DB::statement("
            CREATE TRIGGER restore_stock_after_order_cancellation
            AFTER DELETE ON Produit_Commande
            FOR EACH ROW
            BEGIN
                UPDATE Produit
                SET stock = stock + OLD.QUANTITE
                WHERE Produit.ID = OLD.ID_PRODUIT;
            END;
        ");  

        DB::statement("
            CREATE TRIGGER prevent_comment_on_inactive_product
            BEFORE INSERT ON Commentaire
            FOR EACH ROW
            BEGIN
                DECLARE product_status VARCHAR(50);
                SELECT ETAT INTO product_status FROM Produit WHERE Produit.ID = NEW.ID_PRODUIT;
                IF product_status = 'retiré' THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Impossible d’ajouter un commentaire sur un produit retiré.';
                END IF;
            END;
        ");  

        DB::statement("
            CREATE TRIGGER prevent_empty_order
            BEFORE INSERT ON Commande
            FOR EACH ROW
            BEGIN
                DECLARE product_count INT;
                SELECT COUNT(*) INTO product_count FROM Produit_Commande WHERE Produit_Commande.Commande_ID = NEW.ID;
                IF product_count = 0 THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Une commande ne peut pas être vide.';
                END IF;
            END;
        ");  

        DB::statement("
            CREATE TRIGGER delete_images_on_product_deletion
            AFTER DELETE ON Produit
            FOR EACH ROW
            BEGIN
                DELETE FROM Image
                WHERE Image.ID_PRODUIT = OLD.ID;
            END;
        ");
        
        DB::statement("
            CREATE TRIGGER upgrade_user_to_vip
            AFTER INSERT ON Commande
            FOR EACH ROW
            BEGIN
                DECLARE total_order_value DECIMAL(10, 2);
                SELECT SUM(p.Prix * pc.QUANTITE) INTO total_order_value
                FROM Produit_Commande pc
                JOIN Produit p ON pc.Produit_ID = p.ID
                WHERE pc.Commande_ID = NEW.ID;

                IF total_order_value > 75000 THEN
                    UPDATE Utilisateur
                    SET privilege = 'VIP'
                    WHERE Utilisateur.ID = NEW.ID_UTILISATEUR;
                END IF;
            END;
        ");
        
        DB::statement("
            CREATE TRIGGER limit_products_in_order
            BEFORE INSERT ON Produit_Commande
            FOR EACH ROW
            BEGIN
                DECLARE total_quantity INT;
                SELECT SUM(Quantité) INTO total_quantity FROM Produit_Commande WHERE ID_COMMANDE = NEW.ID_COMMANDE;
                IF total_quantity + NEW.QUANTITE > 100 THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Une commande ne peut pas contenir plus de 100 produits.';
                END IF;
            END;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
