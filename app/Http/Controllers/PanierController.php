<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class PanierController extends Controller
{
    public function index(Request $request){
        $user = \App\Models\Utilisateur::getLoggedUser($request);
        $panier = \App\Models\Commande::getPanier($user);

        $produits = \App\Models\Produit_Commande::getAllProducts($panier["ID"]);

        for ($i=0; $i < sizeof($produits); $i++) {
            unset($produits[$i]["ID_COMMANDE"]);
        }

        return Inertia::render("Panier",[
            "produits"=>$produits,
        ]);
    }

    public function ajouterAuPanier(Request $request){
        $data = $request->post();
        $user = \App\Models\Utilisateur::getLoggedUser($request);
        if(!\App\Models\Commande::where(["ID_UTILISATEUR"=>$user["ID"],"ETAT"=>"panier"])->exists()){
            \App\Models\Commande::createPanier($user);
        }
        $panier = \App\Models\Commande::getPanier($user);
        \App\Models\Produit_Commande::ajoutProduit($panier,\App\Models\Produit::getProduct($data["produit"]));
    }
}
