<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commentaire extends Model
{
    /**
     * Le nom de la table associée au modèle.
     *
     * @var string
     */
    protected $table = 'Commentaire';

    public $timestamps = false;

    /**
     * Les colonnes de la table qui peuvent être assignées en masse.
     *
     * @var array
     */
    protected $fillable = [
        'CONTENU',
        'ID_UTILISATEUR',
        'ID_PRODUIT',
        "DATE"
    ];
}
