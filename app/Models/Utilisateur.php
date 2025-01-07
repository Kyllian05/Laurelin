<?php

namespace App\Models;

use http\Cookie;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Utilisateur extends Model
{
    /**
     * Le nom de la table associée au modèle.
     *
     * @var string
     */
    protected $table = 'Utilisateur';

    public $timestamps = false;

    protected $fillable = [
        "EMAIL",
        "PASSWORD",
        "PRENOM",
        "NOM",
        "TOKEN",
        "TOKENGEN",
    ];
}
