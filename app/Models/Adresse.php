<?php

namespace App\Models;

use App\Domain\Utilisateur\Entities\UtilisateurEntity;
use Illuminate\Database\Eloquent\Model;

class Adresse extends Model
{
    /**
     * Le nom de la table associée au modèle.
     *
     * @var string
     */
    protected $table = 'Adresse';

    /**
     * Les colonnes de la table qui peuvent être assignées en masse.
     *
     * @var array
     */
    protected $fillable = [
        'NUM_RUE',
        'NOM_RUE',
        'ID_UTILISATEUR',
        "ID_VILLE"
    ];

    public $timestamps = false;

    static function getAllUserAdresse(UtilisateurEntity $utilisateur)
    {
        return self::where("ID_UTILISATEUR",$utilisateur->getId())->get();
    }

    static function addAdresse(UtilisateurEntity $utilisateur,String $numRue,String $nomRue,String $nomVille, String $codePostale):self{
        $villeID = \App\Models\Ville::where(["CODE_POSTAL"=>$codePostale,"NOM"=>$nomVille])->firstOrFail()["ID"];
        self::create(["NUM_RUE"=>$numRue,"NOM_RUE"=>$nomRue,"ID_UTILISATEUR"=>$utilisateur->getId(),"ID_VILLE"=>$villeID]);
        return self::where(["NUM_RUE"=>$numRue,"NOM_RUE"=>$nomRue,"ID_UTILISATEUR"=>$utilisateur->getId(),"ID_VILLE"=>$villeID])->firstOrFail();
    }
}
