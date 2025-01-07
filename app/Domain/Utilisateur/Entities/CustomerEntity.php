<?php

namespace App\Domain\Utilisateur\Entities;

use App\Domain\Shared\Role;
use Illuminate\Support\Facades\Date;

class CustomerEntity extends UtilisateurEntity
{
    public function __construct(int $id, string $email, string $password, string $prenom, string $nom, ?string $telephone, string $token, string $tokenGen) {
        parent::__construct($id, $email, $password, $prenom, $nom, $telephone, $token, $tokenGen);
    }

    public function getRole(): Role
    {
        return Role::Customer;
    }

    public function setPassword(string $password): void
    {
        $regex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/";

        if (empty($password)) {
            throw new \InvalidArgumentException("Le mot de passe ne peux pas être vide");
        }
        if (strlen($password) < 6) {
            throw new \InvalidArgumentException("Le mot de passe doit être d'au moins 6 caractères");
        }
        if (!preg_match($regex, $password)) {
            throw new \InvalidArgumentException("Le mot de passe doit contenir une majuscule, une minuscule, un chiffre et un caractère spécial");
        }
        $this->password = hash("sha256", $password);
    }
}
