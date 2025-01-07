<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Inertia\Inertia;

class FavorisController extends Controller{
    public function ajoutFavoris(Request $request){
        $data = $request->post();
        $user = \App\Models\Utilisateur::getLoggedUser($request);
        if($user == null){
            return response("", 404);
        }
        $produit = \App\Models\Produit::getProduct($data['produit']);
        \App\Models\Favoris::ajouterAuxFavoris($produit,$user);
        return response("", 200);
    }

    public function supprimerFavoris(Request $request){
        $data = $request->post();
        $user = \App\Models\Utilisateur::getLoggedUser($request);
        if($user == null){
            return response("", 404);
        }
        $produit = \App\Models\Produit::getProduct($data['produit']);
        \App\Models\Favoris::supprimerDesFavoris($produit,$user);
        return response("", 200);
    }
}
