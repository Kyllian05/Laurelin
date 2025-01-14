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

    protected $primaryKey = "ID";

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
}
