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


    static function getByCodePostal($codePostal){
        return self::where('CODE_POSTAL','LIKE', $codePostal."%")->take(10)->get();
    }
}
