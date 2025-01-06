<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favoris extends Model
{
    protected $table = 'Favoris';
    protected $fillable = ['ID_PRODUIT', 'ID_UTILISATEUR'];
    public $timestamps = false;

    static function isProduitInFavoris(\App\Models\Produit $produit,?\App\Models\Utilisateur $user) : bool{
        if($user == null)return false;
        return self::where(["ID_PRODUIT"=>$produit["ID"], "ID_UTILISATEUR"=>$user["ID"]])->exists() > 0;
    }

    static function ajouterAuxFavoris(\App\Models\Produit $produit,\App\Models\Utilisateur $user){
        self::create(["ID_PRODUIT"=>$produit["ID"], "ID_UTILISATEUR"=>$user["ID"]]);
    }

    static function supprimerDesFavoris(\App\Models\Produit $produit,\App\Models\Utilisateur $user){
        self::where(["ID_PRODUIT"=>$produit["ID"], "ID_UTILISATEUR"=>$user["ID"]])->delete();
    }
}
