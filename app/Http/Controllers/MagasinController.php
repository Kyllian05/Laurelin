<?php

namespace App\Http\Controllers;

use App\Domain\ProductGroup\Services\CategorieService;
use App\Domain\ProductGroup\Services\CollectionService;
use App\Domain\Produit\Services\ProduitService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MagasinController extends Controller
{
    public function __construct(
        private CollectionService $collectionService,
        private CategorieService $categorieService,
        private ProduitService $produitService,
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
        $allSerializedProducts = [];
        foreach($allProducts as $product){
            $allSerializedProducts[] = $this->produitService->serialize($product);
        }
        return Inertia::render("ListeProduit", [
            'produits' => $allSerializedProducts,
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
            'produits' => $allSerializedProducts
        ]);
    }
}
