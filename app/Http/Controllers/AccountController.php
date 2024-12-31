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

            return Inertia::render("Account",[
                "page"=>$page,
                "info"=>[
                    "Prénom"=>$user["PRENOM"],
                    "Nom"=>$user["NOM"],
                    "Téléphone"=>$user["TELEPHONE"],
                    "Adresse mail"=>$user["EMAIL"],
                ],
                "commandes"=>$commandesData
            ]);
        }catch (\Exception $e){
            if($e->getCode() == 518){
                return redirect("/auth")->cookie("redirect","/account",10,null,null,false,false)->withCookie(\Illuminate\Support\Facades\Cookie::forget("TOKEN"));
            }
        }
    }

    function update(Request $request){
        $data = $request->post();
        $user = \App\Models\Utilisateur::getLoggedUser($request);
        if(isset($data["Nom"]) && isset($data["Prénom"]) && isset($data["Téléphone"])){
            \App\Models\Utilisateur::where("ID",$user["ID"])->update(["NOM"=>$data["Nom"],"PRENOM"=>$data["Prénom"],"TELEPHONE"=>$data["Téléphone"]]);
        }
    }
}
