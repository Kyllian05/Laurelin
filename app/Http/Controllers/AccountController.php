<?php

namespace App\Http\Controllers;

use App\Domain\Produit\Services\ProduitService;
use App\Domain\Utilisateur\Services\UtilisateurService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Inertia\Inertia;

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
                throw new \Exception("User not found");
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
            if($e->getCode() == 518){
                return redirect("/auth")->cookie("redirect","/account",10,null,null,false,false)->withCookie(Cookie::forget("TOKEN"));
            }
            throw $e;
        }
    }

    function update(Request $request) {
        $data = $request->post();
        $user = $this->userService->getAuthenticatedUser($request);
        if($user && isset($data["Nom"]) && isset($data["Prénom"]) && isset($data["Téléphone"])) {
            try {
                $this->userService->updateInfo($user, $data["Nom"], $data["Prénom"], $data["Téléphone"]);
            } catch (\Exception $e) {
                $class = explode("\\",get_class($e));
                $class = $class[sizeof($class)-1];
                if($class == "CustomExceptions"){
                    return response($e->getMessage(),$e->getCode());
                }else{
                    \Log::info($e);
                    return response("Erreur inconnue",500);
                }
            }
            return response("success",200);
        } else {
            return response("Accès refusé",520);
        }
    }
}
