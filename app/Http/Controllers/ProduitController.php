<?php

namespace App\Http\Controllers;

use App\Domain\Commentaire\Service\CommentaireService;
use App\Domain\ProductGroup\Services\CategorieService;
use App\Domain\Produit\Services\ProduitService;
use App\Domain\Utilisateur\Services\UtilisateurService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProduitController extends Controller
{
    public function __construct(
        private UtilisateurService $userService,
        private ProduitService $produitService,
        private CategorieService $categorieService,
        private CommentaireService $commentaireService
    ) {}

    public function show(string $id, Request $request)
    {
        if (ctype_digit($id)) {
            $user = $this->userService->getAuthenticatedUser($request);
            $produit = $this->produitService->findById(intval($id));

            // Récupérer si le produit est favoris
            $isFav = false;
            if ($user) {
                $isFav = $this->userService->isFavorite($user, $produit);
            }

            // Récupérer les données des commentaires
            $commentaires = $this->commentaireService->findByProduct($produit);
            $commentairesSerialized = [];
            foreach ($commentaires as $commentaire) {
                if($user != null) {
                    $commentaire->setDeletable($user);
                }
                $commentairesSerialized[] = $commentaire->serialize();
            }

            // Récupérer les produits associés
            $cat = $this->categorieService->findByProduct($produit);
            $produitsAssocie = $this->categorieService->getProducts($cat);
            $dataProduitsAssocie = [];
            foreach ($produitsAssocie as $p) {
                $prodSerialized = $this->produitService->serialize($p);
                $prodSerialized["FAVORITE"] = $user && $this->userService->isFavorite($user, $p);
                $dataProduitsAssocie[] = $prodSerialized;
            }

            return Inertia::render("Produit",[
                "produit" => $this->produitService->serialize($produit),
                "isFavorite" => $isFav,
                "autreProduits" => $dataProduitsAssocie,
                "donneesCommentaires" => $commentairesSerialized,
            ]);
        }
        return response("", 404);
    }

    public function createCommentaire(Request $request){
        $user = $this->userService->getAuthenticatedUser($request);
        if($user == null){
            $e = \App\Domain\Shared\Exceptions::createError(525);
            return response($e->getMessage(), $e->httpCode);
        }
        $data = $request->post();
        $commentaire = $this->commentaireService->create($user, $data["produit"], $data["contenu"]);
        return response($commentaire->serialize(),200);
    }

    public function supprimerCommentaire(Request $request){
        $user = $this->userService->getAuthenticatedUser($request);
        $data = $request->post();
        $this->commentaireService->delete($user, $data['produit']);
    }
}
