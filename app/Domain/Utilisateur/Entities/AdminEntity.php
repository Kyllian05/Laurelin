<?php

namespace App\Domain\Utilisateur\Entities;

use App\Domain\Shared\Role;
use Illuminate\Support\Facades\Date;

class AdminEntity extends UtilisateurEntity
{
    public function __construct(int $id, string $email, string $password, string $prenom, string $nom, ?string $telephone, string $token, string $tokenGen, ?string $code, ?string $codeGen) {
        parent::__construct($id, $email, $password, $prenom, $nom, $telephone, $token, $tokenGen, $code, $codeGen);
    }

    public function getRole(): Role
    {
        return Role::Admin;
    }

    public function setPassword(string $password): void
    {
        if (empty($password)) {
            throw new \InvalidArgumentException("Le mot de passe ne peux pas être vide");
        }

        $this->password = hash("sha256", $password);
    }
}
