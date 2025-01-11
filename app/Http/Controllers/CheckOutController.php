<?php

namespace App\Http\Controllers;

use App\Domain\Adresse\Services\AdresseService;
use App\Domain\Utilisateur\Services\UtilisateurService;
use App\Models\Exceptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Inertia\Inertia;

class CheckOutController extends Controller
{
    public function __construct(
        private UtilisateurService $utilisateurService,
        private AdresseService $adresseService,
    ) {}

    public function index(Request $request){
        // --- Utilisateur ---
        try {
            $user = $this->utilisateurService->getAuthenticatedUser($request);
            if($user == null){
                throw Exceptions::createError(518);
            }
        } catch(\Exception $e) {
            if($e->getCode() == 518){
                return redirect("/auth")->cookie("redirect","/checkout",10,null,null,false,false)->withCookie(Cookie::forget("TOKEN"));
            }else{
                throw $e;
            }
        }

        $userData = [
            "EMAIL" => $user->getEmail(),
            "NOM" => $user->getNom(),
            "PRENOM" => $user->getPrenom(),
        ];

        // --- Adresses ---

        $adresses = $this->utilisateurService->getAdresses($user);
        $adressesSerialized = [];
        foreach($adresses as $adresse){
            $adressesSerialized[] = $adresse->serialize();
        }

        // --- Panier

        $panier = \App\Models\Commande::getPanier($user);
        $produits = \App\Models\Produit_Commande::getAllProducts($panier["ID"])->toArray();

        $result = [];

        foreach($produits as $produit){
            $temp = \App\Models\Produit::where("ID",$produit["ID_PRODUIT"])->firstOrFail();
            $temp["QUANTITE"] = $produit["QUANTITE"];
            $result[] = $temp;
        }

        // --- Render ---

        return Inertia::render("CheckOut",[
            "user" => $userData,
            "adresses" => $adressesSerialized,
            "produits" => $result,
        ]);
    }

    public function valider(Request $request){
        $data = $request->post();

        if($data["livraison"] != "domicile" && $data["livraison"] != "magasin"){
            throw Exceptions::createError(520);
        }

        try{
            $user = $this->utilisateurService->getAuthenticatedUser($request);
            if($user == null){
                throw Exceptions::createError(518);
            }
        }catch(\Exception $e){
            if($e->getCode() == 518){
                return redirect("/auth")->cookie("redirect","/checkout",10,null,null,false,false)->withCookie(\Illuminate\Support\Facades\Cookie::forget("TOKEN"));
            }else{
                throw $e;
            }
        }

        $panier = \App\Models\Commande::getPanier($user);
        $products = \App\Models\Produit_Commande::getAllProducts($panier["ID"]);

        foreach($products as $product){
            \App\Models\Produit_Commande::where([
                "ID_PRODUIT"=>$product["ID_PRODUIT"],
                "ID_COMMANDE"=>$panier["ID"]])
                ->update(["PRIX"=>\App\Models\Produit::where("ID",$product["ID_PRODUIT"])->firstOrFail()["PRIX"]]);
        }

        if($data["livraison"] == "domicile"){
            \App\Models\Commande::where(["ID_UTILISATEUR" => $user->getId(),"ETAT"=>"panier"])->update(["ETAT"=>0,"ID_ADRESSE"=>$data["adresse"],"MODE_LIVRAISON"=>$data["livraison"]]);
        }
    }
}
