<?php

namespace Tests\Feature;

use App\Domain\Adresse\Repositories\AdresseRepository;
use App\Domain\Adresse\Repositories\VilleRepository;
use App\Domain\Commande\Repositories\Database\CommandeRepository;
use App\Domain\Commande\Repositories\Database\ProduitCommandeRepository;
use App\Domain\Commentaire\Repositories\CommentaireRepository;
use App\Domain\Produit\Repositories\ImageRepository;
use App\Domain\Produit\Repositories\ProduitRepository;
use App\Domain\Utilisateur\Repositories\UtilisateurRepository;
use App\Models\Utilisateur;
use Tests\TestCase;

class FeatureTest extends TestCase
{
    private ?string $tokenCookie;

    private ?string $token;

    private \App\Domain\Utilisateur\Repositories\UtilisateurRepository $utilisateurRepository;
    private \App\Domain\Commentaire\Repositories\CommentaireRepository $commentaireRepository;

    private \App\Domain\Produit\Repositories\ProduitRepository $produitRepository;

    private \App\Domain\Adresse\Repositories\VilleRepository $villeRepository;

    private \App\Domain\Adresse\Repositories\AdresseRepository $adresseRepository;

    private \App\Domain\Commande\Repositories\Database\CommandeRepository $commandeRepository;

    private \App\Domain\Produit\Repositories\ImageRepository $imageRepository;

    private \App\Domain\Commande\Repositories\Database\ProduitCommandeRepository $produitCommandeRepository;

    function defineToken(string $email,string $password){
        $user = $this->utilisateurRepository->findByEmail($email);
        assert($user != null);

        $this->token = $user->getToken();

        $reponse = $this->post("/auth/login", [
            \App\Http\Controllers\AuthController::$fields["login"]["fields"][0] => $email,
            \App\Http\Controllers\AuthController::$fields["login"]["fields"][1] => $password,
            \App\Http\Controllers\AuthController::$fields["login"]["checkBoxs"][0] => true
        ]);
        $reponse->assertStatus(200);

        $this->defaultCookies[] = $reponse->getCookie("TOKEN");
    }

    function resetToken(){
        $this->defaultCookies = [];
        $this->token = null;
    }

    function __construct(string $name)
    {
        parent::__construct($name);
        $this->utilisateurRepository = new UtilisateurRepository();
        $this->commentaireRepository = new CommentaireRepository($this->utilisateurRepository);
        $this->produitRepository = new ProduitRepository();
        $this->villeRepository = new VilleRepository();
        $this->adresseRepository = new AdresseRepository($this->villeRepository);
        $this->imageRepository = new ImageRepository();
        $this->produitCommandeRepository = new ProduitCommandeRepository($this->produitRepository,$this->imageRepository);
        $this->commandeRepository = new CommandeRepository($this->produitCommandeRepository,$this->adresseRepository,$this->imageRepository);
    }

    function test_authentication(){
        $this->resetToken();
        $reponse = $this->post("/auth/login", [ //tentative de connection avec un compte existant
            \App\Http\Controllers\AuthController::$fields["login"]["fields"][0] => "admin@admin.com",
            \App\Http\Controllers\AuthController::$fields["login"]["fields"][1] => "admin",
            \App\Http\Controllers\AuthController::$fields["login"]["checkBoxs"][0] => true
        ]);

        $reponse->assertStatus(200);

        $reponse = $this->post("/auth/login", [ //tentative de connection avec un compte inexistant
            \App\Http\Controllers\AuthController::$fields["login"]["fields"][0] => "emailInexistant",
            \App\Http\Controllers\AuthController::$fields["login"]["fields"][1] => "admin",
            \App\Http\Controllers\AuthController::$fields["login"]["checkBoxs"][0] => true
        ]);

        $reponse->assertStatus(401);

    }

    function test_commentaire_interaction(){
        $this->defineToken("admin@admin.com","admin");//on récupere un token d'itentification
        $testProduct = 24;//produit sur lequelle nous allons effectuer les tests

        $user = $this->utilisateurRepository->findByToken($this->token);
        assert($user != null);

        assert(!\App\Models\Commentaire::where(["ID_PRODUIT"=>$testProduct,"ID_UTILISATEUR"=>$user->getId()])->exists());
        //on vérifie qu'il n'y pas de commentaire posté par cet utilisateur sur ce produit

        $reponse = $this->post('/nouveauCommentaire',[
            "produit"=>$testProduct,
            "contenu"=>"Voici un nouveau commentaire"
        ]);
        $reponse->assertStatus(200);

        assert(\App\Models\Commentaire::where(["ID_PRODUIT"=>$testProduct,"ID_UTILISATEUR"=>$user->getId()])->exists());
        //on vérifie qu'il ya bien un commentaire posté par cet utilisateur sur ce produit

        $reponse = $this->post('/supprimerCommentaire',[
            "produit"=>$testProduct
        ]);
        $reponse->assertStatus(200);

        assert(!\App\Models\Commentaire::where(["ID_PRODUIT"=>$testProduct,"ID_UTILISATEUR"=>$user->getId()])->exists());
        //on vérifie qu'il n'y pas de commentaire posté par cet utilisateur sur ce produit
    }

    function test_update_info(){
        $this->defineToken("admin@admin.com","admin");
        $user = $this->utilisateurRepository->findByToken($this->token);
        assert($user != null);

        $newData = [
            "Nom"=>(string)time(),
            "Prénom"=>(string)time(),
            "Téléphone"=>"+3".time()%1000000000,
        ];
        //on génere de nouvelle info qui n'ont pas pu être ajouté par de précédent test

        assert($user->getNom() != $newData["Nom"]);
        assert($user->getPrenom() != $newData["Prénom"]);
        assert($user->getTelephone() != $newData["Téléphone"]);
        //on vérifie que les informations de l'utilisateurs n'ont pas déja la valeurs que nous allons leurs donner

        $reponse = $this->post("/updateInfo", [
            "Nom"=>$newData["Nom"],
            "Prénom"=> $newData["Prénom"],
            "Téléphone"=> $newData["Téléphone"],
        ]);
        $reponse->assertStatus(200);

        $user = $this->utilisateurRepository->findByToken($this->token);
        assert($user != null);
        assert($user->getNom() ==  $newData["Nom"]);
        assert($user->getPrenom() ==  $newData["Prénom"]);
        assert($user->getTelephone() == $newData["Téléphone"]);
    }

    function test_favoris_interaction(){
        $this->defineToken("admin@admin.com","admin");
        $user = $this->utilisateurRepository->findByToken($this->token);
        assert($user != null);

        $produitTest = 24;

        assert(!\App\Models\Favoris::where(["ID_PRODUIT"=>$produitTest,"ID_UTILISATEUR"=>$user->getId()])->exists());
        //on vérifie que le produit n'est pas déja dans les favoris de l'utilisateur

        $reponse = $this->post("/ajouterFavoris", [
            "produit"=>$produitTest,
        ]);
        $reponse->assertStatus(200);

        assert(\App\Models\Favoris::where(["ID_PRODUIT"=>$produitTest,"ID_UTILISATEUR"=>$user->getId()])->exists());
        //on vérifie que le produit a bien été ajouté dans les favoris de l'utilisateur

        $reponse = $this->post("/supprimerFavoris", [
            "produit"=>$produitTest,
        ]);
        $reponse->assertStatus(200);

        assert(!\App\Models\Favoris::where(["ID_PRODUIT"=>$produitTest,"ID_UTILISATEUR"=>$user->getId()])->exists());
        //on vérifie que le produit n'est plus dans les favoris de l'utilisateur
    }

    function test_ajout_interaction(){
        $this->defineToken("admin@admin.com","admin");
        $user = $this->utilisateurRepository->findByToken($this->token);
        assert($user != null);

        $adresses = $this->adresseRepository->findByUser($user);

        $IdVilleTest = 29232;

        foreach ($adresses as $adresse){
            $serialized = $adresse->serialize();
            assert($serialized["VILLE"]["ID"] != $IdVilleTest || $serialized["NUM_RUE"] != 42 || $serialized["NOM_RUE"] != "Rue de test");
        }

        $reponse = $this->post("/adresse/ajout", [
            "Ville"=>[
                "ID"=>$IdVilleTest,
            ],
            "Numéro"=>42,
            "Nom de rue"=>"Rue de test"
        ]);;
        $reponse->assertStatus(200);

        $adresses = $this->adresseRepository->findByUser($user);

        assert(\App\Models\Adresse::where([
            "NUM_RUE"=>42,
            "NOM_RUE"=>"Rue de test",
            "ID_VILLE"=>$IdVilleTest,
            "ID_UTILISATEUR"=>$user->getId()
        ])->exists());

        $adresseModel = \App\Models\Adresse::where([
            "NUM_RUE"=>42,
            "NOM_RUE"=>"Rue de test",
            "ID_VILLE"=>$IdVilleTest,
            "ID_UTILISATEUR"=>$user->getId()
        ])->firstOrFail();

        $reponse = $this->post("/adresse/supprimer", [
            "ID" => $adresseModel["ID"],
        ]);
        $reponse->assertStatus(200);

        assert(!\App\Models\Adresse::where([
            "NUM_RUE"=>42,
            "NOM_RUE"=>"Rue de test",
            "ID_VILLE"=>$IdVilleTest,
            "ID_UTILISATEUR"=>$user->getId()
        ])->exists());
    }

    function test_panier_interaction(){
        $this->defineToken("admin@admin.com","admin");
        $user = $this->utilisateurRepository->findByToken($this->token);
        assert($user != null);

        $IdProduitTest = 24;

        $produits = $this->commandeRepository->getCart($user)->getProducts();

        foreach ($produits as $produit){
            assert($produit instanceof \App\Domain\Commande\Entities\ProduitCommandeEntity);
            assert($produit->getProduit()->id != $IdProduitTest);
        }

        $reponse = $this->post("/panier/ajout", [
            "produit"=>$IdProduitTest,
        ]);
        $reponse->assertStatus(200);

        $produits = $this->commandeRepository->getCart($user)->getProducts();

        $foundState = false;
        foreach ($produits as $produit){
            assert($produit instanceof \App\Domain\Commande\Entities\ProduitCommandeEntity);
            if($produit->getProduit()->id == $IdProduitTest){
                $foundState = true;
            }
        }
        assert($foundState);

        $reponse = $this->post("/panier/supprimer", [
            "produit"=>$IdProduitTest,
        ]);
        $reponse->assertStatus(200);

        $produits = $this->commandeRepository->getCart($user)->getProducts();

        foreach ($produits as $produit){
            assert($produit instanceof \App\Domain\Commande\Entities\ProduitCommandeEntity);
            assert($produit->getProduit()->id != $IdProduitTest);
        }
    }
}
