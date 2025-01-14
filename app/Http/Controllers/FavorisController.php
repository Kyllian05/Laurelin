<?php
namespace App\Http\Controllers;


use App\Domain\Produit\Services\ProduitService;
use App\Domain\Shared\Exceptions;
use App\Domain\Utilisateur\Services\UtilisateurService;
use Illuminate\Http\Request;

class FavorisController extends Controller{

    public function __construct(
        private UtilisateurService $userService,
        private ProduitService $produitService,
    ) {}

    public function ajoutFavoris(Request $request)
    {
        $data = $request->post();
        $user = $this->userService->getAuthenticatedUser($request);
        if($user == null){
            $e = Exceptions::createError(522);
            return response($e->getMessage(), $e->getCode());
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
            $e = Exceptions::createError(525);
            return response($e->getMessage(), $e->getCode());
        }

        $produit = $this->produitService->findById($data['produit']);
        $this->userService->deleteFavoris($user,$produit);
        return response("", 200);
    }
}
