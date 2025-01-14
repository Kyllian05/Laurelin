<?php

namespace App\Http\Controllers;

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
                    "DELETABLE" => $user != null && $comment["ID_UTILISATEUR"] == $user->getId(),
                ];
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
                "donneesCommentaires" => $donneescommantaire,
            ]);
        }
        return response("", 404);
    }

    public function createCommentaire(Request $request){

        //TODO

        $user = $this->userService->getAuthenticatedUser($request);

        $data = $request->post();

        $commentaire = \App\Models\Commentaire::create(["CONTENU"=>$data["contenu"],"ID_UTILISATEUR"=>$user->getId(),"ID_PRODUIT"=>$data["produit"],"DATE"=>date('Y-m-d', time())]);
        $response = $commentaire->toArray();
        unset($response["ID_UTILISATEUR"]);
        unset($response["ID_PRODUIT"]);
        unset($response["id"]);
        $response["PRENOM"] = $user->getPrenom();
        $response["NOM"] = $user->getNom();
        $response["DELETABLE"] = true;
        return response($response,200);
    }

    public function supprimerCommentaire(Request $request){

        //TODO

        $user = $this->userService->getAuthenticatedUser($request);

        $data = $request->post();

        \App\Models\Commentaire::where(["ID_PRODUIT"=>$data["produit"],"ID_UTILISATEUR"=>$user->getId()])->delete();
    }
}
