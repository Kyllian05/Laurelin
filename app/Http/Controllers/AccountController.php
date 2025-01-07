<?php

namespace App\Http\Controllers;

use App\Domain\Utilisateur\Services\UtilisateurService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountController extends Controller
{
    public function __construct(private UtilisateurService $userService) {}

    function index(Request $request,string $page = "info"){
        try{
            $user = $this->userService->getAuthenticatedUser($request);

            if (!$user) {
                throw new \Exception("User not found");
            }

            $commandes = \App\Models\Commande::getAllCommandes($user);

            $commandesData = [];

            foreach($commandes as $commande){
                $temp = [
                    "Date" => $commande["DATE"],
                    "Etat" => $commande["ETAT"],
                    "Mode Livraison" => $commande["MODE_LIVRAISON"],
                    "Produits" => []
                ];

                foreach(\App\Models\Produit_Commande::getAllProducts($commande["ID"]) as $produit){
                    $produitEntity = \App\Models\Produit::getProduct($produit["ID_PRODUIT"]);
                    $temp["Produits"][] = [
                        "Nom" => $produitEntity["NOM"],
                        "Quantité" => $produit["QUANTITE"],
                        "Prix" => $produit["PRIX"],
                    ];
                }

                $commandesData[] = $temp;
            }

            $favoris = [];
            foreach(\App\Models\Favoris::getAllFavoris($user) as $favori){
                $produit  = \App\Models\Produit::getProduct($favori["ID_PRODUIT"]);
                $favoris[] = [
                    "Nom" => $produit["NOM"],
                    "Prix" => $produit["PRIX"],
                    "Image" => \App\Models\Image::get_one_image($produit["ID"])[0],
                    "ID" => $produit["ID"],
                ];
            }

            $adresses = [];
            foreach(\App\Models\Adresse::getAllUserAdresse($user) as $adresse){
                $adresses[] = array(
                    "Numéro" => $adresse["NUM_RUE"],
                    "Rue" => $adresse["NOM_RUE"],
                    "Code Postal" => $adresse["CODE_POSTAL"],
                    "Ville" => \App\Models\Ville::getByCodePostal($adresse["CODE_POSTAL"])["NOM"],
                    "ID" => $adresse["ID"],
                );
            }

            return Inertia::render("Account",[
                "page"=>$page,
                "info"=>[
                    "Prénom"=>$user->getPrenom(),
                    "Nom"=>$user->getNom(),
                    "Téléphone"=>$user->getTelephone(),
                    "Adresse mail"=>$user->getEmail(),
                ],
                "commandes"=>$commandesData,
                "favoris"=>$favoris,
                "adresses" => $adresses,
            ]);
        }catch (\Exception $e){
            if($e->getCode() == 518){
                return redirect("/auth")->cookie("redirect","/account",10,null,null,false,false)->withCookie(\Illuminate\Support\Facades\Cookie::forget("TOKEN"));
            }
            return redirect("/auth")->cookie("redirect","/account",10,null,null,false,false);
        }
    }

    function update(Request $request) {
        $data = $request->post();
        $user = $this->userService->getAuthenticatedUser($request);
        if($user && isset($data["Nom"]) && isset($data["Prénom"]) && isset($data["Téléphone"])) {
            try {
                $this->userService->getRepository()->updateInfo($user, $data["Nom"], $data["Prénom"], $data["Téléphone"]);
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
