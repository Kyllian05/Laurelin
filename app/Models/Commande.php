<?php

namespace App\Models;

use App\Domain\Utilisateur\Entities\UtilisateurEntity;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    /**
     * Le nom de la table associée au modèle.
     *
     * @var string
     */
    protected $table = 'Commande';

    protected $primaryKey = 'ID';

    /**
     * Les colonnes de la table qui peuvent être assignées en masse.
     *
     * @var array
     */
    protected $fillable = [
        'DATE',
        'ETAT',
        'MODE_LIVRAISON',
        'ID_UTILISATEUR',
        'ID_ADRESSE',
    ];

    public $timestamps = false;
}
