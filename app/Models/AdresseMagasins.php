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

    protected $primaryKey = "ID";

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
}
