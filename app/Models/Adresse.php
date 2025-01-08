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
        'CODE_POSTAL',
    ];

    public $timestamps = false;

    static function getAllUserAdresse(UtilisateurEntity $utilisateur)
    {
        return self::where("ID_UTILISATEUR",$utilisateur->getId())->get();
    }

    static function addAdresse(UtilisateurEntity $utilisateur,String $numRue,String $nomRue,String $codePostal):self
    {
        self::create(["NUM_RUE"=>$numRue,"NOM_RUE"=>$nomRue,"CODE_POSTAL"=>$codePostal,"ID_UTILISATEUR"=>$utilisateur->getId()]);
        return self::where(["NUM_RUE"=>$numRue,"NOM_RUE"=>$nomRue,"CODE_POSTAL"=>$codePostal,"ID_UTILISATEUR"=>$utilisateur->getId()])->firstOrFail();
    }
}
