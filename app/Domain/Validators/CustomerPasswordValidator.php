<?php

namespace App\Domain\Validators;

class CustomerPasswordValidator implements PasswordValidatorStrategy
{
    static string $errorMessage = "Le mot de passe doit être d'une longueur de 6 caractères minimum et doit contenir des minuscules, des majuscules, des chiffres et des caractères spéciaux (#,?,!,@,$,%,^,&,*,-)";
    /**
     * @param string $password : Le mot de passe à valider
     * @param string $nom : Le nom de l'utilisateur
     * @param string $prenom : Le prénom de l'utilisateur
     * @return bool True si le mot de passe :
     *  - N'est pas vide
     *  - Fait plus de 5 caractères
     *  - Contient des minuscules, des majuscules, des chiffres et des caractères spéciaux
     */
    public function validate(string $password, string $nom, string $prenom): bool
    {
        $regex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/";

        return !empty($password) && strlen($password) >= 6 && preg_match($regex, $password);
    }

    public function getErrorMessage(): string
    {
        return self::$errorMessage;
    }
}
