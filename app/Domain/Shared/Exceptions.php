<?php

namespace App\Domain\Shared;

class Exceptions
{
    static $messages = [
        512 => "Email invalide",
        514 => "Email déja inscrit",
        513 => "API invalide",
        515 => "Identifiant invalide",
        516 => "Erreur lors de la création du Code de vérification",
        517 => "Email non vérifiée",
        518 => "Token invalide",
    ];

    public static function createError(int $code):CustomExceptions{
        if(!in_array($code,array_keys(Exceptions::$messages))){
            throw new \Exception("Probleme lors de la création d'une exception");
        }
        $message = Exceptions::$messages[$code];
        return new CustomExceptions($message,$code);
    }

    public static function createErrorWithMessage(int $code, string $message):CustomExceptions {
        return new CustomExceptions($message,$code);
    }
}

class CustomExceptions extends \Exception
{
    public function __contruct(string $message,int $code){
        parent::__contruct($message,$code);
    }
}
