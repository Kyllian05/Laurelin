<?php

namespace App\Domain\Utilisateur\Entities;

use App\Domain\Shared\Role;
use App\Domain\Validators\PasswordValidatorStrategy;
use Illuminate\Support\Facades\Date;

class CustomerEntity extends UtilisateurEntity
{
    protected function __construct(int $id, string $email, string $password, string $prenom, string $nom, ?string $telephone, string $token, string $tokenGen, ?string $code, ?string $codeGen, PasswordValidatorStrategy $passwordValidator) {
        parent::__construct($id, $email, $password, $prenom, $nom, $telephone, $token, $tokenGen, $code, $codeGen, $passwordValidator);
    }

    public function getRole(): Role
    {
        return Role::Customer;
    }
}
