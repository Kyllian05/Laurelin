<?php

namespace App\Http\Controllers;

use App\Domain\Utilisateur\Services\UtilisateurService;
use App\Models\Favoris;
use App\Models\Image;
use App\Models\Produit;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProduitController extends Controller
{
    public function __construct(private UtilisateurService $userService) {}

    public function show(string $id, Request $request){
        if (ctype_digit($id)) {
            $user = $this->userService->getAuthenticatedUser($request);
            $produit = Produit::find($id);

            return Inertia::render("Produit",[
                "produit" => $produit,
                "images" => Image::get_all_images($id),
                "isFavorite" => $user && \App\Models\Favoris::isProduitInFavoris($produit, $user),
            ]);
        }
        return response("", 404);
    }

    public function produitData(string $id){
        return response(Produit::find($id));
    }
}
