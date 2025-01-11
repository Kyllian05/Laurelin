<?php

namespace App\Http\Controllers;

use App\Domain\Utilisateur\Services\UtilisateurService;
use App\Models\Exceptions;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PanierController extends Controller
{
    public function __construct(
        private UtilisateurService $utilisateurService,
    ) {}

    public function index(Request $request){

        try{
            $user = $this->utilisateurService->getAuthenticatedUser($request);
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

        $produits = \App\Models\Produit_Commande::getAllProducts($panier["ID"])->toArray();

        $result = [];

        for ($i=0; $i < sizeof($produits); $i++) {
            $quantite = $produits[$i]["QUANTITE"];
            while($produits[$i]["QUANTITE"] > 0){
                $temp = \App\Models\Produit::getProduct($produits[$i]["ID_PRODUIT"]);
                $temp["IMAGE"] = \App\Models\Image::get_one_image($produits[$i]["ID_PRODUIT"]);
                $result[] = $temp;
                $produits[$i]["QUANTITE"]--;
            }
        }

        return Inertia::render("Panier",[
            "produits"=>$result,
        ]);
    }

    public function ajouterAuPanier(Request $request){
        $data = $request->post();
        $user = $this->utilisateurService->getAuthenticatedUser($request);
        if(!\App\Models\Commande::where(["ID_UTILISATEUR"=>$user->getId(),"ETAT"=>"panier"])->exists()){
            \App\Models\Commande::createPanier($user);
        }
        $panier = \App\Models\Commande::getPanier($user);
        \App\Models\Produit_Commande::ajoutProduit($panier,\App\Models\Produit::getProduct($data["produit"]));
    }

    public function supprimerDuPanier(Request $request){
        $data = $request->post();
        $user = $this->utilisateurService->getAuthenticatedUser($request);
        $panier = \App\Models\Commande::getPanier($user);
        \App\Models\Produit_Commande::supprimerProduit($panier,\App\Models\Produit::getProduct($data["produit"]));
    }
}
