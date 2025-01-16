<?php

namespace App\Domain\Utilisateur\Entities;

use App\Domain\Adresse\Entities\AdresseUtilisateur;
use App\Domain\Commande\Entities\CommandeEntity;
use App\Domain\Produit\Entities\ProduitEntity;
use App\Domain\Shared\Role;
use App\Domain\Validators\AdminPasswordValidator;
use App\Domain\Validators\CustomerPasswordValidator;
use App\Domain\Validators\PasswordValidatorStrategy;
use App\Domain\Validators\WeakPasswordValidator;
use Illuminate\Support\Facades\App;

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
    private ?string $code;
    private ?string $codeGen;
    private ?array $favoris = null;
    private ?array $adresses = null;
    private ?array $commandes = null;
    private PasswordValidatorStrategy $passwordValidator;

    protected function __construct(int $id, string $email, string $password, string $prenom, string $nom, ?string $telephone, string $token, string $tokenGen, ?string $code, ?string $codeGen, PasswordValidatorStrategy $passwordValidator) {
        $this->setId($id);
        $this->setEmail($email);
        $this->password = $password;
        $this->setPrenom($prenom);
        $this->setNom($nom);
        $this->telephone = $telephone;
        $this->setToken($token);
        $this->setTokenGen($tokenGen);
        $this->setCode($code);
        $this->setCodeGen($codeGen);
        $this->passwordValidator = $passwordValidator;
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
    public static function utilisateurFactory(int $id, string $email, string $password, string $prenom, string $nom, ?string $telephone, string $token, string $tokenGen, int $privilege, ?string $code, ?string $codeGen): ?UtilisateurEntity {
        if ($privilege == 0) {
            return new CustomerEntity($id, $email, $password, $prenom, $nom, $telephone, $token, $tokenGen, $code, $codeGen, new CustomerPasswordValidator());
        } elseif ($privilege == 1) {
            if (App::environment('local')) {
                $validator = new WeakPasswordValidator();
            } else {
                $validator = new AdminPasswordValidator();
            }
            return new AdminEntity($id, $email, $password, $prenom, $nom, $telephone, $token, $tokenGen, $code, $codeGen, $validator);
        }
        return null;
    }

    public static function createNewUser(int $id, string $email, string $password, string $prenom, string $nom, ?string $telephone, string $token, string $tokenGen, int $privilege): ?UtilisateurEntity {
        if ($privilege == 0) {
            $entity = new CustomerEntity($id, $email, $password, $prenom, $nom, $telephone, $token, $tokenGen, null, null, new CustomerPasswordValidator());
            $entity->setPassword($password);
            return $entity;
        } elseif ($privilege == 1) {
            if (App::environment('local')) {
                $validator = new WeakPasswordValidator();
            } else {
                $validator = new AdminPasswordValidator();
            }
            $entity = new AdminEntity($id, $email, $password, $prenom, $nom, $telephone, $token, $tokenGen, null, null, $validator);
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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function getCodegen(): ?string
    {
        return $this->codeGen;
    }

    /**
     * @throws \Exception : Si l'attribut favori n'a pas été initialisé
     */
    public function getFavoris(): array {
        if (is_null($this->favoris)) {
            throw new \Exception("Get must be called from UtilisateurService");
        }
        return $this->favoris;
    }

    /**
     * @throws \Exception : Si l'attribut adresses n'a pas été initialisé
     */
    public function getAdresses(): array {
        if (is_null($this->adresses)) {
            throw new \Exception("Get adresses must be called from UtilisateurService");
        }
        return $this->adresses;
    }

    /**
     * @throws \Exception : Si l'attribut commandes n'a pas été initialisé
     */
    public function getCommandes(): array {
        if (is_null($this->commandes)) {
            throw new \Exception("Get must be called from UtilisateurService");
        }
        return $this->commandes;
    }

    abstract public function getRole(): Role;

    // ---
    // Setters
    // ---

    /**
     * @param string $password : Le mot de passe en clair
     * @return void
     * @throws \Exception : Si le mot de passe n'est pas valide
     */
    public function setPassword(string $password): void
    {
        if ($this->passwordValidator->validate($password, $this->nom, $this->prenom)) {
            $this->password = hash("sha256", $password);
            return;
        }
        throw new \Exception($this->passwordValidator->getErrorMessage());
    }

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

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function setCodeGen(?string $codeGen): void
    {
        $this->codeGen = $codeGen;
    }

    public function setFavoris(array $favoris): void
    {
        foreach ($favoris as $favori) {
            if (!($favori instanceof ProduitEntity)) {
                throw new \InvalidArgumentException("Le favori n'est pas un produit valide");
            }
        }
        $this->favoris = $favoris;
    }

    public function setAdresses(array $adresses): void
    {
        foreach ($adresses as $adresse) {
            if (!($adresse instanceof AdresseUtilisateur)) {
                throw new \InvalidArgumentException("L'adresse n'est pas une adresse valide");
            }
        }
        $this->adresses = $adresses;
    }

    public function setCommandes(array $commandes): void
    {
        foreach ($commandes as $commande) {
            if (!($commande instanceof CommandeEntity)) {
                throw new \InvalidArgumentException("La commande n'est pas une commande valide");
            }
        }
        $this->commandes = $commandes;
    }

    // Autres fonctions

    /**
     * Vérifie s'il faut régénérer un nouveau token
     * @return void
     */
    public function checkTokenDate(): bool {
        return time() > strtotime($this->tokenGen)+2629800;
    }

    public function isFavorite(ProduitEntity $produit): bool
    {
        if (is_null($this->favoris)) {
            throw new \Exception("Set must be called before from UtilisateurService");
        }
        foreach ($this->favoris as $favori) {
            assert($favori instanceof ProduitEntity);
            if($produit->id == $favori->id){
                return true;
            }
        }
        return false;
    }

    // Static

    public function isUserVerified(): bool
    {
        if ($this->code == null) {
            return true;
        } elseif (empty($this->code)) {
            return true;
        }
        return false;
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
