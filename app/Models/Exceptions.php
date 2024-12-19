<?php

namespace App\Models;

class Exceptions
{
    static $messages = [
        512 => "Email invalide",
        514 => "Email déja inscrit",
        513 => "API invalide",
        515 => "Identifiant invalide",
    ];

    static public function createError(int $code):CustomExceptions{
        if(!in_array($code,array_keys(Exceptions::$messages))){
            throw new \Exception("Probleme lors de la création d'une exception");
        }
        $message = Exceptions::$messages[$code];
        return new CustomExceptions($message,$code);
    }
}

class CustomExceptions extends \Exception
{
    public function __contruct(string $message,int $code){
        parent::__contruct($message,$code);
    }
}
