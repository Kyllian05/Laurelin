<?php

namespace App\Http\Controllers;

use App\Domain\ProductGroup\Services\CategorieService;
use App\Domain\ProductGroup\Services\CollectionService;
use App\Domain\Produit\Services\ProduitService;
use App\Domain\Utilisateur\Services\UtilisateurService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MagasinController extends Controller
{
    public function __construct(
        private CollectionService $collectionService,
        private CategorieService $categorieService,
        private ProduitService $produitService,
        private UtilisateurService $utilisateurService,
    ) {}

    public function list_categories_collections(Request $request){

        return Inertia::render("ListeCatCol",[
            "categories" => $this->categorieService->getAllSerialize(),
            "collections" => $this->collectionService->getAllSerialize(),
        ]);
    }

    public function list_categories(string $name, Request $request){
        $categorie = $this->categorieService->findByName($name);
        if (is_null($categorie)) {
            return response("", 404);
        }
        $allProducts = $this->categorieService->getProducts($categorie);

        // Récupérer l'uitilisateur connecté pour les favoris
        try{
            $user = $this->utilisateurService->getAuthenticatedUser($request);
        }catch(\Exception $e){
            $user = null;
        }

        $allSerializedProducts = $this->produitService->serializes($allProducts);

        if($user != null){
            $favoriteStates = $this->utilisateurService->isFavorites($user,$allProducts);

            for($i=0;$i<sizeof($allSerializedProducts);$i++){
                $allSerializedProducts[$i]['FAVORITE'] = $favoriteStates[$allSerializedProducts[$i]['ID']];
            }
        }else{
            for($i=0;$i<sizeof($allSerializedProducts);$i++){
                $allSerializedProducts[$i]['FAVORITE'] = false;
            }
        }

        return Inertia::render("ListeProduit", [
            'produits' => $allSerializedProducts,
            'categories' => $categorie->getId(),
        ]);
    }

    public function list_collections(string $name, Request $request){
        $collection = $this->collectionService->findByName($name);
        if (is_null($collection)) {
            return response("", 404);
        }
        $allProducts = $this->collectionService->getProducts($collection);

        $allSerializedProducts = $this->produitService->serializes($allProducts);

        return Inertia::render("ListeProduit", [
            'produits' => $allSerializedProducts,
            'collections' => $collection->getId(),
        ]);
    }
}
