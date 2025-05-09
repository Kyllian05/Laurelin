<?php

namespace App\Http\Controllers;

use App\Domain\Commentaire\Entities\CommentaireEntity;
use App\Domain\Commentaire\Service\CommentaireService;
use App\Domain\ProductGroup\Services\CategorieService;
use App\Domain\Produit\Services\ProduitService;
use App\Domain\Utilisateur\Services\UtilisateurService;
use App\Models\Commentaire;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Domain\Shared\CustomExceptions;

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
            try {
                $user = $this->userService->getAuthenticatedUser($request);
            } catch (CustomExceptions $e) {
                $user = null;
            }
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
                assert($commentaire instanceof CommentaireEntity);
                if($user != null) {
                    $commentaire->setDeletable($user);
                }
                $commentairesSerialized[] = $commentaire->serialize();
            }

            // Récupérer les produits associés
            $cat = $this->categorieService->findByProduct($produit);
            $produitsAssocie = $this->categorieService->getProducts($cat);
            $dataProduitsAssocie = $this->produitService->serializes($produitsAssocie);

            if($user != null) {
                $favoriteStates = $this->userService->isFavorites($user,$produitsAssocie);

                for($i=0;$i<sizeof($dataProduitsAssocie);$i++){
                    $dataProduitsAssocie[$i]['FAVORITE'] = $favoriteStates[$dataProduitsAssocie[$i]['ID']];
                }
            }else{
                for($i=0;$i<sizeof($dataProduitsAssocie);$i++){
                    $dataProduitsAssocie[$i]['FAVORITE'] = false;
                }
            }

            return Inertia::render("Produit",[
                "produit" => $this->produitService->serialize($produit),
                "isFavorite" => $isFav,
                "autreProduits" => $dataProduitsAssocie,
                "donneesCommentaires" => $commentairesSerialized,
                "Categorie" => $produit->categorie,
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
