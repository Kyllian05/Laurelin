<?php

namespace App\Domain\Utilisateur\Entities;

use App\Domain\Shared\Role;

abstract class UtilisateurEntity
{
    private int $id;
    private string $email;
    protected string $password; // Le hash du mot de passe
    private string $prenom;
    private string $nom;
    private ?string $telephone;
    private string $token;
    private string $tokenGen;

    public function __construct(int $id, string $email, string $password, string $prenom, string $nom, ?string $telephone, string $token, string $tokenGen) {
        $this->setId($id);
        $this->setEmail($email);
        $this->password = $password;
        $this->setPrenom($prenom);
        $this->setNom($nom);
        $this->telephone = $telephone;
        $this->setToken($token);
        $this->setTokenGen($tokenGen);
    }

    /**
     * @param int $id
     * @param string $email
     * @param string $password
     * @param string $prenom
     * @param string $nom
     * @param ?string $telephone
     * @param string $token
     * @param string $tokenGen
     * @param int $privilege
     * @return ?UtilisateurEntity
     */
    public static function utilisateurFactory(int $id, string $email, string $password, string $prenom, string $nom, ?string $telephone, string $token, string $tokenGen, int $privilege): ?UtilisateurEntity {
        if ($privilege == 0) {
            return new CustomerEntity($id, $email, $password, $prenom, $nom, $telephone, $token, $tokenGen);
        } elseif ($privilege == 1) {
            return new AdminEntity($id, $email, $password, $prenom, $nom, $telephone, $token, $tokenGen);
        }
        return null;
    }

    public static function createNewUser(int $id, string $email, string $password, string $prenom, string $nom, ?string $telephone, string $token, string $tokenGen, int $privilege): ?UtilisateurEntity {
        if ($privilege == 0) {
            $entity = new CustomerEntity($id, $email, $password, $prenom, $nom, $telephone, $token, $tokenGen);
            $entity->setPassword($password);
            return $entity;
        } elseif ($privilege == 1) {
            $entity = new AdminEntity($id, $email, $password, $prenom, $nom, $telephone, $token, $tokenGen);
            $entity->setPassword($password);
            return $entity;
        }
        return null;
    }

    // ---
    // Getters
    // ---
    public function getId(): int {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getTokenGen(): string
    {
        return $this->tokenGen;
    }

    abstract public function getRole(): Role;

    // ---
    // Setters
    // ---
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setEmail(string $email): void
    {
        // Vérifie que l'email est valide
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("L'email fourni n'est pas valide : $email");
        }
        $this->email = $email;
    }

    /**
     * @param string $password : Le mot de passe en clair
     * @return void
     */
    abstract public function setPassword(string $password): void;

    public function setPrenom(string $prenom): void
    {
        if (empty($prenom)) {
            throw new \InvalidArgumentException("Le prénom ne peux pas être vide");
        }
        $this->prenom = $prenom;
    }

    public function setNom(string $nom): void
    {
        if (empty($nom)) {
            throw new \InvalidArgumentException("Le nom ne peux pas être vide");
        }
        $this->nom = $nom;
    }

    public function setTelephone(string $telephone): void
    {
        // Vérifie que le téléphone est valide
        $regex = "/^\+[0-9]{1,15}$/";
        if (!preg_match($regex, $telephone)) {
            throw new \InvalidArgumentException("Le numéro de téléphone fourni n'est pas valide : $telephone");
        }
        $this->telephone = $telephone;
    }

    public function setToken(string $token): void
    {
        if (empty($token)) {
            throw new \InvalidArgumentException("Le token ne peux pas être vide");
        }
        $this->token = $token;
    }

    public function setTokenGen(string $tokenGen): void
    {
        $this->tokenGen = $tokenGen;
    }

    /**
     * Vérifie s'il faut régénérer un nouveau token
     * @return void
     */
    public function checkTokenDate(): bool {
        return time() > strtotime($this->tokenGen)+2629800;
    }

    public static function generateRandomString($length) : string {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
