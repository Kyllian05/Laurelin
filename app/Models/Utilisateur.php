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

    static function getLoggedUser(Request $request) : ?Utilisateur{
        $current = null;
        try{
            if(session()->has('EMAIL') && session()->has('PASSWORD')){
                $current = Utilisateur::login(session('EMAIL'),session('PASSWORD'));
            }else if($request->hasCookie("TOKEN")){
                $current = Utilisateur::loginWithToken($request->cookie("TOKEN"));
            }
        }catch(\Exception $e){
            if($e->getCode() == 517){
                $current = null;
            }else if($e->getCode() == 518){
                \Illuminate\Support\Facades\Cookie::queue(\Illuminate\Support\Facades\Cookie::forget("TOKEN"));
                $current = null;
            }else{
                throw $e;
            }
        }
        return $current;
    }

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

        self::loginWithToken($user["TOKEN"]);

        return Utilisateur::where("EMAIL",$email)->where("PASSWORD",$password)->firstOrFail();
    }

    static function loginWithToken(string $token) : ?Utilisateur{
        try{
            $result = Utilisateur::where("TOKEN",$token)->firstOrFail();
            if($result == null){
                return null;
            }
            $tokengen = strtotime($result["TOKENGEN"])+2629800;
            if(time() > $tokengen){
                $newToken = self::generateToken();
                Utilisateur::where("TOKEN",$token)->update(["TOKEN"=>$newToken,"TOKENGEN"=>date ('Y-m-d H:i:s',time())]);
                return null;
            }
            return $result;
        }catch(\Exception $e){
            \Log::info($e);
            $class = explode("\\",get_class($e));
            $class = $class[sizeof($class)-1];
            if($class == "RecordNotFoundException"){
                throw \App\Models\Exceptions::createError(515);
            }else if($class == "ModelNotFoundException"){
                throw \App\Models\Exceptions::createError(518);
            } else{
                throw $e;
            }
        }
    }

    static function changePassword(int $ID, string $token, string $newPassword){
        self::where(["ID"=>$ID,"TOKEN"=>$token])->update(["PASSWORD"=>hash("sha256",$newPassword)]);
        $newToken = self::generateToken();
        Utilisateur::where(["TOKEN"=>$token,"ID"=>$ID])->update(["TOKEN"=>$newToken,"TOKENGEN"=>date ('Y-m-d H:i:s',time())]);
    }
}
