<?php

namespace App\Http\Controllers;

use App\Models\Adresse;
use App\Models\Commande;
use App\Models\Exceptions;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CheckOutController extends Controller
{
    public function index(Request $request){
        try{
            $user = \App\Models\Utilisateur::getLoggedUser($request);
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

        $userData = [
            "EMAIL" => $user["EMAIL"],
            "NOM" => $user["NOM"],
            "PRENOM" => $user["PRENOM"],
        ];

        $adresses = \App\Models\Adresse::getAllUserAdresse($user)->toArray();

        for($i = 0; $i < count($adresses); $i++){
            $ville = \App\Models\Ville::where("ID",$adresses[$i]["ID_VILLE"])->firstOrFail();
            $adresses[$i]["VILLE"] = $ville["NOM"];
            $adresses[$i]["CODE_POSTAL"] = $ville["CODE_POSTAL"];
        }

        return Inertia::render("CheckOut",[
            "user" => $userData,
            "adresses" => $adresses,
        ]);
    }

    public function valider(Request $request){
        $data = $request->post();

        try{
            $user = \App\Models\Utilisateur::getLoggedUser($request);
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
            \App\Models\Commande::where(["ID_UTILISATEUR" => $user["ID"],"ETAT"=>"panier"])->update(["ETAT"=>0,"ID_ADRESSE"=>$data["adresse"],"MODE_LIVRAISON"=>$data["livraison"]]);
        }
    }
}
