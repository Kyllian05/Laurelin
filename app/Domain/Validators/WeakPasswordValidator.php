<?php

namespace App\Domain\Validators;

class WeakPasswordValidator implements PasswordValidatorStrategy
{
    static string $errorMessage = "Le mot de passe doit être d'une longueur de 4 caractères minimum.";
    /**
     * Permet de réduire les éxigences de sécurité pour le développement
     * @param string $password : Le mot de passe à valider
     * @param string $nom : Le nom de l'utilisateur
     * @param string $prenom : Le prénom de l'utilisateur
     * @return bool True si le mot de passe :
     *  - N'est pas vide
     *  - Fait plus de 3 caractères
     */
    public function validate(string $password, string $nom, string $prenom): bool
    {
        return !empty($password) && strlen($password) >= 4;
    }

    public function getErrorMessage(): string
    {
        return self::$errorMessage;
    }
}
