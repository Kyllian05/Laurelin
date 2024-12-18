<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exceptions extends Model
{
    static $codes = [512,513,514];

    static $messages = [
        512 => "Email invalide",
        513 => "Exception non existantes",
        514 => "Email déja inscrit"
    ];

    static public function createError(int $code):CustomExceptions{
        if(!in_array($code,Exceptions::$codes)){
            return createError(513);
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