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
        "NOM",
    ];

    static function register(string $firstName, string $lastName, string $email, string $password){
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            throw \App\Models\Exceptions::createError(512);
        }
        try{
            Utilisateur::create(["EMAIL"=>$email,"PASSWORD"=>$password,"PRENOM"=>$firstName,"NOM"=>$lastName]);
        }catch(\Exception $e){
            if($e->getCode() == 23000){
                throw \App\Models\Exceptions::createError(514);
            }else{
                throw $e;
            }
        }
    }
}
