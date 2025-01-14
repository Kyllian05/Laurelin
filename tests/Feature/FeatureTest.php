<?php

namespace Tests\Feature;

use App\Domain\Commentaire\Repositories\CommentaireRepository;
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

        $save = [
            "Nom"=>$user->getNom(),
            "Prénom"=>$user->getPrenom(),
            "Téléphone"=>$user->getTelephone(),
        ];

        if($save["Téléphone"] == null){
            $save["Téléphone"] = "+30000000000";
        }

        assert($user->getNom() != "Nom de test");
        assert($user->getPrenom() != "Prenom de test");
        assert($user->getTelephone() != "+30000000000");
        //on vérifie que les informations de l'utilisateurs n'ont pas déja la valeurs que nous allons leurs donner

        $reponse = $this->post("/updateInfo", [
            "Nom"=>"Nom de test",
            "Prénom"=>"Prenom de test",
            "Téléphone"=>"+30000000000",
        ]);
        $reponse->assertStatus(200);

        $user = $this->utilisateurRepository->findByToken($this->token);
        assert($user != null);
        assert($user->getNom() == "Nom de test");
        assert($user->getPrenom() == "Prenom de test");
        assert($user->getTelephone() == "+30000000000");

        $reponse = $this->post("/updateInfo", [
            "Nom"=>$save["Nom"],
            "Prénom"=>$save["Prénom"],
            "Téléphone"=>$save["Téléphone"],
        ]);
        $reponse->assertStatus(200);

        $user = $this->utilisateurRepository->findByToken($this->token);
        assert($user != null);
        assert($user->getNom() == $save["Nom"]);
        assert($user->getPrenom() == $save["Prénom"]);
        assert($user->getTelephone() == $save["Téléphone"]);
    }
}
