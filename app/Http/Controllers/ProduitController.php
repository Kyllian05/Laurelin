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
                "produit" => $this->produitService->serialize($produit),
                "isFavorite" => $isFav
            ]);
        }
        return response("", 404);
    }
}
