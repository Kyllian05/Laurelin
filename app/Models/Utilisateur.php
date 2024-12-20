<?php

namespace App\Models;

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

    static function register(string $firstName, string $lastName, string $email, string $password) : Utilisateur{
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            throw \App\Models\Exceptions::createError(512);
        }

        if(Utilisateur::where("EMAIL",$email)->exists()){
            throw \App\Models\Exceptions::createError(514);
        }
        $token = Utilisateur::generateToken();
        Utilisateur::create(["EMAIL"=>$email,"PASSWORD"=>hash("sha256",$password),"PRENOM"=>$firstName,"NOM"=>$lastName,"TOKEN"=>$token,"TOKENGEN"=>date ('Y-m-d H:i:s', time())]);
        $user = Utilisateur::where("EMAIL",$email)->first();
        try{
            \App\Models\Code::generateCode($user["ID"]);
        }catch (\Exception $e){
            Utilisateur::where("ID",$user["ID"])->delete();
            throw \App\Models\Exceptions::createError(516);
        }
        return $user;
    }

    static function generateToken() : string{
        $allToken = array();
        Utilisateur::all()->each(function($utilisateur){
            $allToken[] = $utilisateur["TOKEN"];
        });
        $currentToken = self::generateRandomString(32);
        while(in_array($currentToken, $allToken)){
            $currentToken = self::generateToken(32);
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

    static function login(string $email, string $password): Utilisateur{
        try{
            $user = Utilisateur::where("EMAIL",$email)->where("PASSWORD",$password)->firstOrFail();
        }catch(\Exception $e){
            $class = explode("\\",get_class($e));
            $class = $class[sizeof($class)-1];
            if($class == "RecordNotFoundException"){
                throw \App\Models\Exceptions::createError(515);
            }else{
                throw $e;
            }
        }

        $verified = \App\Models\Code::isUserVerified($user["ID"]);
        if(!$verified){
            throw  \App\Models\Exceptions::createError(517);
        }

        return Utilisateur::where("EMAIL",$email)->where("PASSWORD",$password)->first();
    }

    static function loginWithToken(string $token) : Utilisateur{
        try{
            return Utilisateur::where("TOKEN",$token)->firstOrFail();
        }catch(\Exception $e){
            $class = explode("\\",get_class($e));
            $class = $class[sizeof($class)-1];
            if($class == "RecordNotFoundException"){
                throw \App\Models\Exceptions::createError(515);
            }else{
                throw $e;
            }
        }
    }
}
