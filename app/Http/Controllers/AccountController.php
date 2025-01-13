<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountController extends Controller
{
    function index(Request $request,string $page = "info"){
        try{
            $user = \App\Models\Utilisateur::getLoggedUser($request);
            if($user == null){
                return redirect("/auth")->cookie("redirect","/account",10,null,null,false,false);
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
                $ville = \App\Models\Ville::where("ID",$adresse["ID_VILLE"])->firstOrFail();
                $adresses[] = array(
                    "Numéro" => $adresse["NUM_RUE"],
                    "Rue" => $adresse["NOM_RUE"],
                    "Code Postal" => $ville["CODE_POSTAL"],
                    "Ville" => $ville["NOM"],
                    "ID" => $adresse["ID"],
                );
            }

            return Inertia::render("Account",[
                "page"=>$page,
                "info"=>[
                    "Prénom"=>$user["PRENOM"],
                    "Nom"=>$user["NOM"],
                    "Téléphone"=>$user["TELEPHONE"],
                    "Adresse mail"=>$user["EMAIL"],
                ],
                "commandes"=>$commandesData,
                "favoris"=>$favoris,
                "adresses" => $adresses,
            ]);
        }catch (\Exception $e){
            if($e->getCode() == 518){
                return redirect("/auth")->cookie("redirect","/account",10,null,null,false,false)->withCookie(\Illuminate\Support\Facades\Cookie::forget("TOKEN"));
            }else{
                throw $e;
            }
        }
    }

    function update(Request $request){
        $data = $request->post();
        try{
            $user = \App\Models\Utilisateur::getLoggedUser($request);
            if(isset($data["Nom"]) && isset($data["Prénom"]) && isset($data["Téléphone"])){
                \App\Models\Utilisateur::where("ID",$user["ID"])->update(["NOM"=>$data["Nom"],"PRENOM"=>$data["Prénom"],"TELEPHONE"=>$data["Téléphone"]]);
            }else{
                throw \App\Models\Exceptions::createError(521);
            }
        }catch(\Exception $e){
            if($e instanceof \App\Models\CustomExceptions){
                return response($e->getMessage(),$e->getCode());
            }
        }
    }

}
