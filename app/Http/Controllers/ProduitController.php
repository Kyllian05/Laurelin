<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Commentaire;
use App\Models\Favoris;
use App\Models\Image;
use App\Models\Produit;
use App\Models\User;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProduitController extends Controller
{
    public function show(string $id, Request $request){
        if (ctype_digit($id)) {
            $user = \App\Models\Utilisateur::getLoggedUser($request);
            $produit = Produit::where("ID",$id)->firstOrFail();
            $id_categorie = $produit["ID_CATEGORIE"];
            $nom_categorie = Categorie::where("ID",$id_categorie)->firstOrFail()["NOM"];

            $commentairedonnees = Commentaire::where("ID_PRODUIT",$produit->ID)->get()->toArray();

            $donneescommantaire = []; // Tableau final avec les données enrichies

            foreach ($commentairedonnees as $comment) {
                $utilisateur = Utilisateur::select('NOM', 'PRENOM')->where("ID", $comment["ID_UTILISATEUR"])->first();
                $donneescommantaire[] = [
                    'CONTENU' => $comment["CONTENU"],
                    'ID_UTILISATEUR' => $comment["ID_UTILISATEUR"],
                    'ID_PRODUIT' => $comment["ID_PRODUIT"],
                    'NOM' => $utilisateur ? $utilisateur->NOM : 'Non trouvé',
                    'PRENOM' => $utilisateur ? $utilisateur->PRENOM : 'Non trouvé',
                    'DATE' => $comment["DATE"],
                ];
            }


            return Inertia::render("Produit",[
                "produit" => $produit,
                "images" => Image::get_all_images($id),
                "isFavorite" => \App\Models\Favoris::isProduitInFavoris($produit,$user),
                "autreProduits" => Categorie::get_products($nom_categorie),
                "donneesCommentaires" => $donneescommantaire,
            ]);
        }
        return response("", 404);
    }

    public function produitData(string $id){
        return response(Produit::find($id));
    }

    public function getProduitPicture(string $id,Request $request){
        return response(\App\Models\Image::get_one_image($id));
    }
}
