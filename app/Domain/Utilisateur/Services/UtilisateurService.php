<?php

namespace App\Domain\Utilisateur\Services;

use App\Domain\Utilisateur\Entities\UtilisateurEntity;
use App\Domain\Utilisateur\Repositories\UtilisateurRepository;
use App\Domain\Shared\Exceptions as CustomExceptions;
use Illuminate\Http\Request;

class UtilisateurService
{
    public function __construct(
        private UtilisateurRepository $userRepository
    ) {}

    public function getRepository(): UtilisateurRepository
    {
        return $this->userRepository;
    }

    /**
     * @throws \App\Domain\Shared\CustomExceptions
     */
    public function getAuthenticatedUser(Request $request): ?UtilisateurEntity {
        try {
            if (session()->has('TOKEN')) {
                return $this->loginWithToken(session('TOKEN'));
            } elseif ($request->hasCookie("TOKEN")) {
                return $this->loginWithToken($request->cookie("TOKEN"));
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 518) {
                \Illuminate\Support\Facades\Cookie::queue(\Illuminate\Support\Facades\Cookie::forget("TOKEN"));
            }
            throw $e;
        }
        return null;
    }

    /**
     * @throws \App\Domain\Shared\CustomExceptions : Si l'utilisateur n'existe pas ou si identifiant invalide
     */
    public function login(string $email, string $password): UtilisateurEntity
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user || $user->getPassword() != hash('sha256', $password)) {
            throw CustomExceptions::createError(515);
        }
        // --- A changer ---
        $verified = \App\Models\Code::isUserVerified($user->getId());
        // ---
        if (!$verified) {
            throw CustomExceptions::createError(517);
        }
        session(["TOKEN"=>$user->getToken()]);
        return $this->loginWithToken($user->getToken());
    }

    /**
     * @throws \App\Domain\Shared\CustomExceptions : Si l'utilisateur n'existe pas ou si identifiant invalide
     */
    public function loginWithToken(string $token): UtilisateurEntity
    {
        $user = $this->userRepository->findByToken($token);
        if (!$user) {
            throw CustomExceptions::createError(515);
        }
        if ($user->checkTokenDate()) {
            $newToken = $this->generateToken();
            $this->userRepository->updateToken($user, $newToken);
            throw CustomExceptions::createError(518);
        }
        // --- A changer ---
        $verified = \App\Models\Code::isUserVerified($user->getId());
        // ---
        if (!$verified) {
            throw CustomExceptions::createError(517);
        }
        return $user;
    }

    public function register(string $firstName, string $lastName, string $email, string $password): UtilisateurEntity
    {
        if ($this->userRepository->findByEmail($email)) {
            throw CustomExceptions::createError(514);
        }
        $token = $this->generateToken();
        try {
            $user = $this->userRepository->create($email, $password, $firstName, $lastName, $token, time());
        } catch (\InvalidArgumentException $exception) {
            throw CustomExceptions::createErrorWithMessage(519, $exception->getMessage());
        }

        try{
            // --- A changer ---
            \App\Models\Code::generateCode($user->getId());
            // ---
        }catch (\Exception $e){
            $this->userRepository->delete($user);
            throw CustomExceptions::createError(516);
        }

        session(["TOKEN"=>$user->getToken()]);
        return $user;
    }

    public function generateToken(): string
    {
        $allToken = $this->userRepository->getAllToken();
        $currentToken = UtilisateurEntity::generateRandomString(32);
        while(in_array($currentToken, $allToken)) {
            $currentToken = UtilisateurEntity::generateRandomString(32);
        }
        return $currentToken;
    }

    public function changePassword(int $id, string $token, string $newPassword) {
        try {
            $user = $this->userRepository->updatePassword($id, $token, $newPassword);
        } catch (\InvalidArgumentException $exception) {
            throw CustomExceptions::createErrorWithMessage(519, $exception->getMessage());
        }
        if (!$user) {
            throw CustomExceptions::createError(515);
        }
        $newToken = $this->generateToken();
        $this->userRepository->updateToken($user, $newToken);
    }
}
