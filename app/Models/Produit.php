<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    /**
     * Le nom de la table associée au modèle.
     *
     * @var string
     */
    protected $table = 'Produit';

    /**
     * Les colonnes de la table qui peuvent être assignées en masse.
     *
     * @var array
     */
    protected $fillable = [
        'NOM',
        'MATERIAUX',
        'DESCRIPTION',
        'PRIX',
        'ANNEE_CREATION',
        'ETAT',
        'STOCK',
        'ID_CATEGORIE',
        'ID_COLLECTION',
    ];
}
