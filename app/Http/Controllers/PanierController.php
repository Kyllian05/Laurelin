<?php

namespace App\Http\Controllers;

use App\Models\Exceptions;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PanierController extends Controller
{
    public function index(Request $request){

        try{
            $user = \App\Models\Utilisateur::getLoggedUser($request);
            if($user == null){
                throw Exceptions::createError(518);
            }
        }catch(\Exception $e){
            if($e->getCode() == 518){
                return redirect("/auth")->cookie("redirect","/panier",10,null,null,false,false)->withCookie(\Illuminate\Support\Facades\Cookie::forget("TOKEN"));
            }else{
                throw $e;
            }
        }
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

    public function supprimerDuPanier(Request $request){
        $data = $request->post();
        $user = \App\Models\Utilisateur::getLoggedUser($request);
        $panier = \App\Models\Commande::getPanier($user);
        \App\Models\Produit_Commande::supprimerProduit($panier,\App\Models\Produit::getProduct($data["produit"]));
    }
}
