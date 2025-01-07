<?php

namespace App\Models;

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

    static function getAllUserAdresse(\App\Models\Utilisateur $utilisateur){
        return self::where("ID_UTILISATEUR",$utilisateur["ID"])->get();
    }

    static function addAdresse(\App\Models\Utilisateur $utilisateur,String $numRue,String $nomRue,String $codePostal):self{
        self::create(["NUM_RUE"=>$numRue,"NOM_RUE"=>$nomRue,"CODE_POSTAL"=>$codePostal,"ID_UTILISATEUR"=>$utilisateur["ID"]]);
        return self::where(["NUM_RUE"=>$numRue,"NOM_RUE"=>$nomRue,"CODE_POSTAL"=>$codePostal,"ID_UTILISATEUR"=>$utilisateur["ID"]])->firstOrFail();
    }
}
