<?php

namespace App\Domain\Utilisateur\Repositories;

use App\Domain\Shared\Exceptions;
use App\Domain\Utilisateur\Entities\UtilisateurEntity;
use App\Models\Utilisateur as UtilisateurModel;

class UtilisateurRepository
{
    public function findByID(int $id): ?UtilisateurEntity
    {
        $utilisateurModel = UtilisateurModel::find($id);
        if ($utilisateurModel) {
            return UtilisateurEntity::utilisateurFactory(
                $id,
                $utilisateurModel->EMAIL,
                $utilisateurModel->PASSWORD,
                $utilisateurModel->PRENOM,
                $utilisateurModel->NOM,
                $utilisateurModel->TELEPHONE,
                $utilisateurModel->TOKEN,
                $utilisateurModel->TOKENGEN,
                $utilisateurModel->PRIVILEGE,
                $utilisateurModel->CODE,
                $utilisateurModel->CODEGEN
            );
        }
        return null;
    }

    public function findByEmail(string $email): ?UtilisateurEntity
    {
        $utilisateurModel = UtilisateurModel::where("EMAIL",$email)->first();
        if ($utilisateurModel) {
            return UtilisateurEntity::utilisateurFactory(
                $utilisateurModel->ID,
                $utilisateurModel->EMAIL,
                $utilisateurModel->PASSWORD,
                $utilisateurModel->PRENOM,
                $utilisateurModel->NOM,
                $utilisateurModel->TELEPHONE,
                $utilisateurModel->TOKEN,
                $utilisateurModel->TOKENGEN,
                $utilisateurModel->PRIVILEGE,
                $utilisateurModel->CODE,
                $utilisateurModel->CODEGEN
            );
        }
        return null;
    }

    public function findByToken(string $token): ?UtilisateurEntity
    {
        $utilisateurModel = UtilisateurModel::where("TOKEN",$token)->first();
        if ($utilisateurModel) {
            return UtilisateurEntity::utilisateurFactory(
                $utilisateurModel->ID,
                $utilisateurModel->EMAIL,
                $utilisateurModel->PASSWORD,
                $utilisateurModel->PRENOM,
                $utilisateurModel->NOM,
                $utilisateurModel->TELEPHONE,
                $utilisateurModel->TOKEN,
                $utilisateurModel->TOKENGEN,
                $utilisateurModel->PRIVILEGE,
                $utilisateurModel->CODE,
                $utilisateurModel->CODEGEN
            );
        }
        return null;
    }

    public function getAllToken(): array
    {
        return UtilisateurModel::pluck('TOKEN')->toArray();
    }

    public function getAllCodes(): array
    {
        return UtilisateurModel::pluck('CODE')->toArray();
    }

    public function updateToken(UtilisateurEntity $utilisateurEntity, string $newToken): void {
        $newDate = time();
        UtilisateurModel::where("TOKEN",$utilisateurEntity->getToken())->update(["TOKEN"=>$newToken,"TOKENGEN"=>date ('Y-m-d H:i:s',$newDate)]);
        $utilisateurEntity->setToken($newToken);
        $utilisateurEntity->setTokenGen($newDate);
    }

    public function updatePassword(int $id, string $token, string $newPassword): ?UtilisateurEntity {
        $user = $this->findByID($id);
        if ($user->getToken() == $token) {
            $user->setPassword($newPassword);
            UtilisateurModel::where(["ID"=>$user->getId(),"TOKEN"=>$user->getToken()])->update(["PASSWORD"=>$user->getPassword()]);
            return $user;
        }
        return null;
    }

    public function updateInfo(UtilisateurEntity $utilisateurEntity, string $nom, string $prenom, string $telephone): void {

        try{
            $utilisateurEntity->setNom($nom);
            $utilisateurEntity->setPrenom($prenom);
            $utilisateurEntity->setTelephone($telephone);
        }catch (\Exception $exception){
            throw Exceptions::createError(526);
        }

        UtilisateurModel::where("ID",$utilisateurEntity->getId())->update([
            "NOM"=>$utilisateurEntity->getNom(),
            "PRENOM"=>$utilisateurEntity->getPrenom(),
            "TELEPHONE"=>$utilisateurEntity->getTelephone()
        ]);
    }

    public function create(string $email, string $password, string $prenom, string $nom, string $token, string $tokenGen): UtilisateurEntity {
        $user = UtilisateurEntity::createNewUser(
            -1,
            $email,
            $password,
            $prenom,
            $nom,
            "",
            $token,
            $tokenGen,
            0
        );
        $userModel = UtilisateurModel::create([
            "EMAIL" => $user->getEmail(),
            "PASSWORD" => $user->getPassword(),
            "PRENOM" => $user->getPrenom(),
            "NOM" => $user->getNom(),
            "PRIVILEGE" => 0,
            "TOKEN" => $user->getToken(),
            "TOKENGEN"=> date('Y-m-d H:i:s', $user->getTokenGen()),
        ]);
        $user->setId($userModel->id);
        return $user;
    }

    public function delete(UtilisateurEntity $utilisateurEntity): void {
        UtilisateurModel::where("ID",$utilisateurEntity->getId())->delete();
    }

    public function updateCode(UtilisateurEntity $utilisateurEntity, ?string $newCode): void
    {
        $newDate = time();
        UtilisateurModel::where("ID",$utilisateurEntity->getId())->update(["CODE"=>$newCode,"CODEGEN"=>date ('Y-m-d H:i:s', $newDate)]);
        $utilisateurEntity->setCode($newCode);
        $utilisateurEntity->setCodeGen($newDate);
    }
}
