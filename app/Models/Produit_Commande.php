<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit_Commande extends Model
{
    /**
     * Le nom de la table associée au modèle.
     *
     * @var string
     */
    protected $table = 'Produit_Commande';

    /**
     * Les colonnes de la table qui peuvent être assignées en masse.
     *
     * @var array
     */
    protected $fillable = [
        'QUANTITE',
        'ID_PRODUIT',
        'ID_COMMANDE',
        "TAILLE",
        "PRIX",
    ];

    public $timestamps = false;

    static function getAllProducts(int $commande_id){
        return self::where("ID_COMMANDE", $commande_id)->get();
    }

    static function ajoutProduit(\App\Models\Commande $commande, \App\Models\Produit $produit){
        self::create([
            "QUANTITE"=>1,
            "TAILLE"=>0,
            "ID_PRODUIT"=>$produit["ID"],
            "ID_COMMANDE"=>$commande["ID"],
            "PRIX"=>null
        ]);
    }
}
