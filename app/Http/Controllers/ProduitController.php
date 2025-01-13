<?php

namespace App\Http\Controllers;

use App\Domain\ProductGroup\Services\CategorieService;
use App\Domain\Produit\Services\ProduitService;
use App\Domain\Utilisateur\Services\UtilisateurService;
use App\Models\Exceptions;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Nette\NotImplementedException;

class ProduitController extends Controller
{
    public function __construct(
        private UtilisateurService $userService,
        private ProduitService $produitService,
        private CategorieService $categorieService,
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
            $commentairedonnees = \App\Models\Commentaire::where("ID_PRODUIT",$produit->id)->get()->toArray();

            $donneescommantaire = []; // Tableau final avec les données enrichies

            foreach ($commentairedonnees as $comment) {
                $utilisateur = $this->userService->findById($comment["ID_UTILISATEUR"]);
                $donneescommantaire[] = [
                    'CONTENU' => $comment["CONTENU"],
                    'ID_UTILISATEUR' => $comment["ID_UTILISATEUR"],
                    'ID_PRODUIT' => $comment["ID_PRODUIT"],
                    'NOM' => $utilisateur ? $utilisateur->getNom() : ' ',
                    'PRENOM' => $utilisateur ? $utilisateur->getPrenom() : 'Anonyme',
                    'DATE' => $comment["DATE"],
                ];
            }

            // Récupérer les produits associés
            $cat = $this->categorieService->findByProduct($produit);
            $produitsAssocie = $this->categorieService->getProducts($cat);
            $dataProduitsAssocie = [];
            foreach ($produitsAssocie as $p) {
                $dataProduitsAssocie[] = $this->produitService->serialize($p);
            }

            return Inertia::render("Produit",[
                "produit" => $this->produitService->serialize($produit),
                "isFavorite" => $isFav,
                "autreProduits" => $dataProduitsAssocie,
                "donneesCommentaires" => $donneescommantaire,
            ]);
        }
        return response("", 404);
    }

    public function getProduitPicture(string $id, Request $request ){
        //TODO
        throw Exceptions::createError(531);
    }

    public function createCommentaire(Request $request){
        //TODO
        throw Exceptions::createError(531);
    }

    public function supprimerCommentaire(Request $request){
        //TODO
        throw Exceptions::createError(531);
    }
}
