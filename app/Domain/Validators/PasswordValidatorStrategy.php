<?php

namespace App\Domain\Validators;

interface PasswordValidatorStrategy
{
    /**
     * @param string $password : Le mot de passe à valider
     * @param string $nom : Le nom de l'utilisateur
     * @param string $prenom : Le prénom de l'utilisateur
     * @return bool : True si le mot de passe respecte les éxigences de sécurité
     */
    public function validate(string $password, string $nom, string $prenom): bool;

    /**
     * @return string : Le méssage d'erreur à envoyer à l'utilisateur si le mot de passe n'est pas valide
     */
    public function getErrorMessage(): string;
}
