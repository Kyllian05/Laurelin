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
        "TOKEN",
        "TOKENGEN",
    ];

    static function register(string $firstName, string $lastName, string $email, string $password){
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            throw \App\Models\Exceptions::createError(512);
        }
        try{
            $token = Utilisateur::generateToken();
            Utilisateur::create(["EMAIL"=>$email,"PASSWORD"=>$password,"PRENOM"=>$firstName,"NOM"=>$lastName,"TOKEN"=>$token,"TOKENGEN"=>date ('Y-m-d H:i:s', time())]);
        }catch(\Exception $e){
            if($e->getCode() == 23000){
                throw \App\Models\Exceptions::createError(514);
            }else{
                throw $e;
            }
        }
    }

    static function generateToken() : string{
        $allToken = array();
        Utilisateur::all()->each(function($utilisateur){
            $allToken[] = $utilisateur["TOKEN"];
        });
        $currentToken = self::generateRandomString(32);
        while(in_array($currentToken, $allToken)){
            $currentToken = self::generateToken(20);
        }
        return $currentToken;
    }

    static protected function generateRandomString($length) : string {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
