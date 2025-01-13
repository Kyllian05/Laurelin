<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdresseMagasins extends Model
{
    /**
     * Le nom de la table associée au modèle.
     *
     * @var string
     */
    protected $table = 'AdresseMagasins';

    /**
     * Les colonnes de la table qui peuvent être assignées en masse.
     *
     * @var array
     */
    protected $fillable = [
        'ID_VILLE',
        'ADRESSE',
    ];

    public $timestamps = false;

    static function getMagasins(string $codepostale){
        $citys = \App\Models\Ville::getByCodePostal($codepostale);

        $result = [];
        foreach ($citys as $city){
            $magasins = \App\Models\AdresseMagasins::where('ID_VILLE', $city["ID"])->get();
            foreach ($magasins as $magasin){
                $temp = $magasin;
                $temp["CODEPOSTAL"] = $city["CODE_POSTAL"];
                $temp["VILLE"] = $city["NOM"];
                $result[] = $temp;
            }
        }
        return $result;
    }
}
