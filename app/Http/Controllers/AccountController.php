<?php

namespace App\Http\Controllers;

use App\Domain\Produit\Services\ProduitService;
use App\Domain\Shared\Exceptions as CustomExceptions;
use App\Domain\Utilisateur\Services\UtilisateurService;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Inertia\Inertia;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AccountController extends Controller
{
    public function __construct(
        private UtilisateurService $userService,
        private ProduitService $produitService
    ) {}

    function index(Request $request,string $page = "info"){
        try{
            $user = $this->userService->getAuthenticatedUser($request);

            if (!$user) {
                throw CustomExceptions::createError(518);
            }

            // --- Commandes ---
            $commandes = $this->userService->getCommandes($user);
            $commandesSerialized = [];

            foreach($commandes as $commande){
                $commandesSerialized[] = $commande->serialize();
            }

            // --- Favoris ---

            $favorisSerialized = $this->produitService->serializes($this->userService->getFavoris($user));

            // --- Adresses ---

            $adresses = $this->userService->getAdresses($user);
            $adressesSerialized = [];
            foreach($adresses as $adresse){
                $adressesSerialized[] = $adresse->serialize();
            }

            // --- Render ---

            return Inertia::render("Account",[
                "page"=>$page,
                "info"=>[
                    "Prénom"=>$user->getPrenom(),
                    "Nom"=>$user->getNom(),
                    "Téléphone"=>$user->getTelephone(),
                    "Adresse mail"=>$user->getEmail(),
                ],
                "commandes" => $commandesSerialized,
                "favoris" => $favorisSerialized,
                "adresses" => $adressesSerialized,
            ]);
        }catch (\Exception $e){
            if($e->getCode() == 401){
                return redirect("/auth")->cookie("redirect","/account",10,null,null,false,false)->withCookie(Cookie::forget("TOKEN"));
            }
            throw $e;
        }
    }

    function update(Request $request) {
        $data = $request->post();
        try{
            $user = $this->userService->getAuthenticatedUser($request);
            if($user == null){
                throw CustomExceptions::createError(525);
            }
            if(isset($data["Nom"]) && isset($data["Prénom"]) && isset($data["Téléphone"])){
                $this->userService->updateInfo($user, $data["Nom"], $data["Prénom"], $data["Téléphone"]);
            }else{
                throw CustomExceptions::createError(521);
            }
        }catch(\Exception $e){
            return response($e->getMessage(),$e->getCode());
        }
    }
}
