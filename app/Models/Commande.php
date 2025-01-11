<?php

namespace App\Models;

use App\Domain\Utilisateur\Entities\UtilisateurEntity;
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

    static function getAllCommandes(UtilisateurEntity $utilisateur){
        return self::where(["ID_UTILISATEUR" => $utilisateur->getId()])->where("ETAT","!=","panier")->get()->sortByDesc("DATE");
    }

    static function createPanier(UtilisateurEntity $utilisateur){
        self::create([
            "DATE" => date ('Y-m-d H:i:s', time()),
            "ETAT" => "panier",
            "MODE_LIVRAISON"=>null,
            "ID_UTILISATEUR" => $utilisateur->getId(),
            "ID_ADRESSE" => null]);
    }

    static function getPanier(UtilisateurEntity $utilisateur){
        try{
            return self::where(["ID_UTILISATEUR" => $utilisateur->getId(),"ETAT"=>"PANIER"])->firstOrFail();
        }catch(\Exception $e){
            self::createPanier($utilisateur);
            return self::getPanier($utilisateur);
        }
    }
}
