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
            if($e->getCode() == 518){
                $user = null;
            }else{
                throw $e;
            }
        }

        $allSerializedProducts = [];
        foreach($allProducts as $product){
            $productSerialized = $this->produitService->serialize($product);
            $productSerialized["FAVORITE"] = $user && $this->utilisateurService->isFavorite($user, $product);
            $allSerializedProducts[] = $productSerialized;
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
        $allSerializedProducts = [];
        foreach($allProducts as $product){
            $allSerializedProducts[] = $this->produitService->serialize($product);
        }
        return Inertia::render("ListeProduit", [
            'produits' => $allSerializedProducts,
            'collections' => $collection->getId(),
        ]);
    }
}
