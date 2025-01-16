<?php

namespace App\Domain\Utilisateur\Services;

use App\Domain\Adresse\Repositories\AdresseRepository;
use App\Domain\Commande\Repositories\Database\CommandeRepository;
use App\Domain\Produit\Entities\ProduitEntity;
use App\Domain\Utilisateur\Entities\UtilisateurEntity;
use App\Domain\Utilisateur\Repositories\FavorisRepository;
use App\Domain\Utilisateur\Repositories\UtilisateurRepository;
use App\Domain\Shared\Exceptions as CustomExceptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UtilisateurService
{
    public function __construct(
        private UtilisateurRepository $userRepository,
        private FavorisRepository $favorisRepository,
        private AdresseRepository $adresseRepository,
        private CommandeRepository $commandeRepository,
    ) {}

    public function findById(int $id): ?UtilisateurEntity
    {
        return $this->userRepository->findByID($id);
    }

    public function findByEmail(string $email): ?UtilisateurEntity
    {
        return $this->userRepository->findByEmail($email);
    }

    public function updateInfo(UtilisateurEntity $utilisateurEntity, string $nom, string $prenom, string $telephone): void
    {
        $this->userRepository->updateInfo($utilisateurEntity, $nom, $prenom, $telephone);
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
            } else {
                throw $e;
            }
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
        session(["TOKEN"=>$user->getToken()]);
        return $this->loginWithToken($user->getToken());
    }

    public function logout(): \Illuminate\Http\JsonResponse
    {
        session()->forget("TOKEN");
        $response = response()->json(['message' => 'Déconnexion réussie']);
        $response->headers->clearCookie('TOKEN');
        return $response;
    }

    /**
     * @throws \App\Domain\Shared\CustomExceptions : Si l'utilisateur n'existe pas ou si identifiant invalide
     */
    public function loginWithToken(string $token): UtilisateurEntity
    {
        $user = $this->userRepository->findByToken($token);
        if (!$user) {
            throw CustomExceptions::createError(518);
        }
        if ($user->checkTokenDate()) {
            $newToken = $this->generateToken();
            $this->userRepository->updateToken($user, $newToken);
            throw CustomExceptions::createError(518);
        }
        if (!$user->isUserVerified()) {
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
        } catch (\Exception $exception) {
            throw CustomExceptions::createError(519,$exception->getMessage());
        }
        // Envoi du code de vérification de l'email
        if(config("app.email_verification")){
            $this->sendNewCode($user);
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

    public function generateCode(): int
    {
        $allCodes = $this->userRepository->getAllCodes();
        $currentCode = random_int(100000,999999);
        while(in_array($currentCode, $allCodes)) {
            $currentCode = random_int(100000,999999);
        }
        return $currentCode;
    }

    public function changePassword(int $id, string $token, string $newPassword) {
        try {
            $user = $this->userRepository->updatePassword($id, $token, $newPassword);
        } catch (\InvalidArgumentException $exception) {
            throw CustomExceptions::createError(519, $exception->getMessage());
        }
        if (!$user) {
            throw CustomExceptions::createError(515);
        }
        $newToken = $this->generateToken();
        $this->userRepository->updateToken($user, $newToken);
    }

    public function sendNewCode(UtilisateurEntity $utilisateurEntity): void
    {
        $code = $this->generateCode();
        $this->userRepository->updateCode($utilisateurEntity, $code);
        Mail::to($utilisateurEntity->getEmail())->send(new \App\Mail\EmailVerification($utilisateurEntity->getId(),$code));
    }

    /**
     * @throws \App\Domain\Shared\CustomExceptions : Si le code n'est pas correct
     */
    public function verifyCode(int $id, string $code): void
    {
        $utilisateurEntity = $this->userRepository->findById($id);
        if ($utilisateurEntity->getCode() == $code) {
            $genTimestamp = strtotime($utilisateurEntity->getCodegen());
            $currentTimestamp = strtotime(date ('Y-m-d H:i:s', time()));
            // Si le code est dépassé de 10 minutes
            if($currentTimestamp - $genTimestamp > 10*60) {
                $this->sendNewCode($utilisateurEntity);
                return;
            }
            $this->userRepository->updateCode($utilisateurEntity, null);
            return;
        }
        throw CustomExceptions::createError(530);
    }

    // --- Favoris ---
    public function getFavoris(UtilisateurEntity $utilisateurEntity): array
    {
        $this->favorisRepository->getFavoris($utilisateurEntity);
        return $utilisateurEntity->getFavoris();
    }

    public function isFavorite(UtilisateurEntity $utilisateurEntity, ProduitEntity $produitEntity): bool
    {
        // Update les favoris
        $this->getFavoris($utilisateurEntity);
        return $utilisateurEntity->isFavorite($produitEntity);
    }

    public function isFavorites(UtilisateurEntity $utilisateurEntity, array $produitsEntity): bool
    {
        $this->getFavoris($utilisateurEntity);

        $result = [];

        foreach ($produitsEntity as $produitEntity) {
            assert($produitEntity instanceof ProduitEntity);
            $result[$produitEntity->id] = $utilisateurEntity->isFavorite($produitEntity);
        }
        return $result;
    }

    public function addFavoris(UtilisateurEntity $utilisateurEntity, ProduitEntity $produitEntity): void
    {
        $this->favorisRepository->addFavoris($utilisateurEntity, $produitEntity);
    }

    public function deleteFavoris(UtilisateurEntity $utilisateurEntity, ProduitEntity $produitEntity): void
    {
        $this->favorisRepository->deleteFavoris($utilisateurEntity, $produitEntity);
    }

    // --- Adresses ---
    public function getAdresses(UtilisateurEntity $utilisateurEntity): array
    {
        $utilisateurEntity->setAdresses($this->adresseRepository->findByUser($utilisateurEntity));
        return $utilisateurEntity->getAdresses();
    }

    // --- Commandes ---
    public function getCommandes(UtilisateurEntity $utilisateurEntity): array
    {
        $utilisateurEntity->setCommandes($this->commandeRepository->findByUser($utilisateurEntity));
        return $utilisateurEntity->getCommandes();
    }
}
