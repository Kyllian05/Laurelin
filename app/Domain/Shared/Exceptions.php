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
        519 => "Impossible de supprimer un produit d'une commande",
        520 => "Méthode de livraison invalide",
        521 => "Champs manquants",
        522 => "Vous devez être connecté pour ajouter des produits en favoris",
        523 => "Cette adresse est liée à une commande",
        524 => "Informations de paiement invalide",
        525 => "Vous devez être connecté",
        526 => "Informations invalides",
        530 => "Code invalide",
        531 => "Pas implémenté",
    ];

    static $statusCode = [
        512 => 400, // Généralement, 400 est utilisé pour des erreurs de requêtes malformées.
        514 => 409, // 409 Conflict - "Email déjà inscrit" correspond à un conflit avec les données existantes.
        513 => 400, // 400 Bad Request - "API invalide" indique que la requête est malformée ou invalide.
        515 => 401, // 401 Unauthorized - "Identifiant invalide" pourrait être considéré comme une tentative non autorisée.
        516 => 500, // 500 Internal Server Error - "Erreur lors de la création du Code de vérification".
        517 => 403, // 403 Forbidden - "Email non vérifié", l'accès peut être refusé pour cette raison.
        518 => 401, // 401 Unauthorized - "Token invalide" signifie une authentification défaillante.
        519 => 400, // 400 Bad Request - "Impossible de supprimer un produit d'une commande" est une erreur liée à la requête.
        520 => 400, // 400 Bad Request - "Méthode de livraison invalide" reflète une entrée incorrecte.
        521 => 400, // 400 Bad Request - "Champs manquants" est une erreur de validation d'entrée.
        522 => 401, // 401 Unauthorized - "Vous devez être connecté pour ajouter des produits en favoris".
        523 => 409, // 409 Conflict - "Cette adresse est liée à une commande" est un conflit d'état.
        524 => 400, // 400 Bad Request - "Informations de paiement invalide".
        525 => 401, // 401 Unauthorized - "Vous devez être connecté".
        526 => 400,
        530 => 400, // 400 Bad Request - "Code invalide".
        531 => 501, // 501 Not Implemented - Indique que cette fonctionnalité n'est pas implémentée.
    ];

    public static function createError(int $code):CustomExceptions{
        if(!in_array($code,array_keys(Exceptions::$messages))){
            throw new \Exception("Probleme lors de la création d'une exception");
        }
        $message = Exceptions::$messages[$code];
        $result = new CustomExceptions($message,$code);
        $result->httpCode = self::$statusCode[$code];
        return $result;
    }
}

class CustomExceptions extends \Exception{
    public int $httpCode;

    public function __contruct(string $message,int $code){
        parent::__contruct($message,$code);
    }
}
