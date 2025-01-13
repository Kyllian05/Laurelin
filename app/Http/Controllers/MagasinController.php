<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Collection;
use App\Models\Produit;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MagasinController extends Controller
{
    public function list_categories_collections(Request $request){

        return Inertia::render("ListeCatCol",[
            "categories" => Categorie::all(),
            "collections" => Collection::all(),
        ]);
    }

    public function list_categories(string $name, Request $request){
        $categorie = Categorie::where("NOM",$name)->firstOrFail();
        $IDCategorie = $categorie["ID"];
        $produits = Categorie::get_products($name);

        try{
            $user = \App\Models\Utilisateur::getLoggedUser($request);
        }catch(\Exception $e){
            if($e->getCode() == 518){
                $user = null;
            }else{
                throw $e;
            }
        }

        $produits = json_decode(json_encode($produits), true);

        for($i = 0; $i < sizeof($produits); $i++){
            if($user != null){
                $produits[$i]["FAVORITE"] = \App\Models\Favoris::where(["ID_UTILISATEUR"=>$user["ID"],"ID_PRODUIT"=>$produits[$i]["ID"]])->exists();
            }else{
                $produits[$i]["FAVORITE"] = false;
            }
        }

        return Inertia::render("ListeProduit", [
            'produits' => $produits,
            'categories' => $IDCategorie,
        ]);
    }

    public function list_collections(string $name, Request $request){
        $collections = Collection::where("NOM",$name)->firstOrFail();
        $IDCollections = $collections["ID"];

        return Inertia::render("ListeProduit", [
            'produits' => Collection::get_products($name),
            'collections' => $IDCollections,
        ]);
    }
}
