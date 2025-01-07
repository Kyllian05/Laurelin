<?php

namespace App\Http\Controllers;

use App\Domain\Produit\Services\ProduitService;
use App\Domain\Utilisateur\Services\UtilisateurService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProduitController extends Controller
{
    public function __construct(
        private UtilisateurService $userService,
        private ProduitService $produitService,
    ) {}

    public function show(string $id, Request $request)
    {
        if (ctype_digit($id)) {
            $user = $this->userService->getAuthenticatedUser($request);
            $produit = $this->produitService->findById(intval($id));

            $isFav = false;
            if ($user) {
                $isFav = $this->userService->isFavorite($user, $produit);
            }

            return Inertia::render("Produit",[
                "produit" => [
                    "id" => $produit->id,
                    "nom" => $produit->nom,
                    "description" => $produit->description,
                    "prix" => $produit->prix,
                    "materiaux" => $produit->materiaux,
                    "annee_cration" => $produit->anneeCreation,
                    "images" => $this->produitService->getImages($produit),
                ],
                "isFavorite" => $isFav
            ]);
        }
        return response("", 404);
    }

    public function ajoutFavoris(Request $request)
    {
        $data = $request->post();
        $user = $this->userService->getAuthenticatedUser($request);
        if($user == null){
            return response("", 404);
        }
        $produit = $this->produitService->findById($data['produit']);
        $this->userService->addFavoris($user,$produit);
        return response("", 200);
    }

    public function supprimerFavoris(Request $request)
    {
        $data = $request->post();
        $user = $this->userService->getAuthenticatedUser($request);
        if($user == null){
            return response("", 404);
        }

        $produit = $this->produitService->findById($data['produit']);
        $this->userService->deleteFavoris($user,$produit);
        return response("", 200);
    }
}
