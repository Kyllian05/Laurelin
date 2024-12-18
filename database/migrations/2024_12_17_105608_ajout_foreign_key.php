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
            ALTER TABLE Produit
                ADD CONSTRAINT fk_produit_categorie
                FOREIGN KEY (ID_CATEGORIE) REFERENCES categorie(ID)
        ");

        DB::statement("
            ALTER TABLE Produit
                ADD CONSTRAINT fk_produit_collection
                    FOREIGN KEY (ID_COLLECTION) REFERENCES Collection(ID)
        ");

        DB::statement("
            ALTER TABLE Adresse
                ADD CONSTRAINT fk_adresse_utilisateur
                    FOREIGN KEY (ID_UTILISATEUR) REFERENCES Utilisateur(ID)
        ");

        DB::statement("
            ALTER TABLE Adresse
                ADD CONSTRAINT fk_adresse_ville
                    FOREIGN KEY (CODE_POSTAL) REFERENCES Ville(CODE_POSTAL)
        ");

        DB::statement("
            ALTER TABLE Commande
                ADD CONSTRAINT fk_commande_utilisateur
                    FOREIGN KEY (ID_UTILISATEUR) REFERENCES Utilisateur(ID)
        ");


        DB::statement("
            ALTER TABLE Commande
                ADD CONSTRAINT fk_commande_adresse
                    FOREIGN KEY (ID_ADRESSE) REFERENCES Adresse(ID)
        ");

        DB::statement("
            ALTER TABLE Produit_Commande
                ADD CONSTRAINT pk_produit_commande_produit
                    PRIMARY KEY (ID_PRODUIT, ID_COMMANDE)
        ");

        DB::statement("
            ALTER TABLE Produit_Commande
                ADD CONSTRAINT fk_produit_commande_produit
                    FOREIGN KEY (ID_PRODUIT) REFERENCES Produit(ID)
        ");


        DB::statement("
            ALTER TABLE Produit_Commande
                ADD CONSTRAINT fk_produit_commande_commande
                    FOREIGN KEY (ID_COMMANDE) REFERENCES Commande(ID)
        ");


        DB::statement("
            ALTER TABLE Commentaire
                ADD CONSTRAINT pk_commentaire_produit
                    PRIMARY KEY (ID_PRODUIT, ID_UTILISATEUR)
        ");

        DB::statement("
            ALTER TABLE Commentaire
                ADD CONSTRAINT fk_commentaire_produit
                    FOREIGN KEY (ID_PRODUIT) REFERENCES Produit(ID)
        ");


        DB::statement("
            ALTER TABLE Commentaire
                ADD CONSTRAINT fk_commentaire_utilisateur
                    FOREIGN KEY (ID_UTILISATEUR) REFERENCES Utilisateur(ID)
        ");

        DB::statement("
            ALTER TABLE Collection
                ADD CONSTRAINT Annee_correct
                    CHECK (1965 < Annee < 2025)
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
