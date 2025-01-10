<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    /**
     * Le nom de la table associée au modèle.
     *
     * @var string
     */
    protected $table = 'Commande';

    /**
     * Les colonnes de la table qui peuvent être assignées en masse.
     *
     * @var array
     */
    protected $fillable = [
        'DATE',
        'ETAT',
        'MODE_LIVRAISON',
        'ID_UTILISATEUR',
        'ID_ADRESSE',
    ];

    public $timestamps = false;

    static function getAllCommandes(Utilisateur $utilisateur){
        return self::where(["ID_UTILISATEUR" => $utilisateur["ID"]])->where("ETAT","!=","panier")->get()->sortByDesc("DATE");
    }

    static function createPanier(Utilisateur $utilisateur){
        self::create([
            "DATE" => date ('Y-m-d H:i:s', time()),
            "ETAT" => "panier",
            "MODE_LIVRAISON"=>null,
            "ID_UTILISATEUR" => $utilisateur["ID"],
            "ID_ADRESSE" => null]);
    }

    static function getPanier(Utilisateur $utilisateur){
        try{
            return self::where(["ID_UTILISATEUR" => $utilisateur["ID"],"ETAT"=>"PANIER"])->firstOrFail();
        }catch(\Exception $e){
            self::createPanier($utilisateur);
            return self::getPanier($utilisateur);
        }
    }
}
