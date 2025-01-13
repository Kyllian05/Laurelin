<?php

namespace App\Domain\Validators;

class AdminPasswordValidator implements PasswordValidatorStrategy
{
    static string $errorMessage = "Le mot de passe doit être d'une longueur de 10 caractères minimum et doit contenir des minuscules, des majuscules, des chiffres et des caractères spéciaux (#,?,!,@,$,%,^,&,*,-). Et il ne doit pas contenir votre nom ou prénom";
    /**
     * @param string $password : Le mot de passe à valider
     * @param string $nom : Le nom de l'utilisateur
     * @param string $prenom : Le prénom de l'utilisateur
     * @return bool True si le mot de passe :
     *  - N'est pas vide
     *  - Fait plus de 9 caractères
     *  - Contient des minuscules, des majuscules, des chiffres et des caractères spéciaux
     *  - Ne contient pas le prénom ou le nom de l'utilisateur
     */
    public function validate(string $password, string $nom, string $prenom): bool
    {
        $regex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{10,}$/";

        if (!empty($password) && strlen($password) >= 10 && preg_match($regex, $password)) {
            $pswdLower = strtolower($password);
            return !str_contains($pswdLower, strtolower($nom)) && !str_contains($pswdLower, strtolower($prenom));
        }
        return false;
    }

    public function getErrorMessage(): string
    {
        return self::$errorMessage;
    }
}
