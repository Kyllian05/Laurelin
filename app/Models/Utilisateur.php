<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        "NOM"
    ];

    static function register(string $firstName, string $lastName, string $email, string $password){
        Utilisateur::create(["EMAIL"=>$email,"PASSWORD"=>$password,"PRENOM"=>$firstName,"NOM"=>$lastName]);
    }
}
