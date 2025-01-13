<?php

namespace App\Models;

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
        519 => "Impossible de supprimer un produit d'une commande",
        520 => "Méthode de livraison invalide",
        521 => "Champs manquants",
        522 => "Vous devez être connecté pour ajouter des produits en favoris",
        523 => "Cette adresse est liée à une commande",
        524 => "Informations de paiement invalide",
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
