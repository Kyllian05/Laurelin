<?php

namespace App\Http\Controllers;

use App\Models\Favoris;
use App\Models\Image;
use App\Models\Produit;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProduitController extends Controller
{
    public function show(string $id, Request $request){
        if (ctype_digit($id)) {
            $user = \App\Models\Utilisateur::getLoggedUser($request);
            $produit = Produit::find($id);

            return Inertia::render("Produit",[
                "produit" => $produit,
                "images" => Image::get_all_images($id),
                "isFavorite" => \App\Models\Favoris::isProduitInFavoris($produit,$user),
            ]);
        }
        return response("", 404);
    }

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
