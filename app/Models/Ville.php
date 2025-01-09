<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ville extends Model
{
    /**
     * Le nom de la table associée au modèle.
     *
     * @var string
     */
    protected $table = 'Ville';

    /**
     * Les colonnes de la table qui peuvent être assignées en masse.
     *
     * @var array
     */
    protected $fillable = [
        'NOM',
        'CODE_POSTAL',
    ];

    static function getByCodePostal($codePostal){
        return self::where('CODE_POSTAL','LIKE', $codePostal."%")->take(10)->get();
    }
}
